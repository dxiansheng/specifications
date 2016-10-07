<?php

namespace Pbmedia\ScoreMatcher;

use Illuminate\Support\Collection;
use Pbmedia\ScoreMatcher\Interfaces\Attribute;
use Pbmedia\ScoreMatcher\Interfaces\CanBeSpecified;
use Pbmedia\ScoreMatcher\Specifications;

class Matcher implements CanBeSpecified
{
    use HasSpecifications;

    protected $candidates;

    public function __construct()
    {
        $this->candidates = new Collection;
    }

    public function addCandidate(CanBeSpecified $candidate)
    {
        $this->candidates->push($candidate);
    }

    public function getCandidates(): Collection
    {
        return $this->candidates;
    }

    public function getScoresByAttribute(Attribute $attribute): Collection
    {
        return $this->candidates->map(function ($candidate) use ($attribute) {
            $attributeScore = $candidate->specifications()->get($attribute);

            return $attributeScore ? $attributeScore->getScoreValue() : null;
        });
    }

    public function getNormalizedScoresByAttribute(Attribute $attribute): Collection
    {
        $scores = $this->getScoresByAttribute($attribute);

        $max = $scores->max();

        return $scores->map(function ($score) use ($max) {
            return is_null($score) ? null : ($score / $max);
        });
    }

    public function getMatchingScoreByAttributeScore(AttributeScore $attributeScore): Collection
    {
        $attribute  = $attributeScore->getAttribute();
        $scoreValue = $attributeScore->getScoreValue();

        $scores = $this->getScoresByAttribute($attribute);

        $scoreToCompareTo = $scoreValue / $scores->max();

        return $this->getNormalizedScoresByAttribute($attribute)->map(function ($normalizedScore) use ($scoreToCompareTo) {
            if ($normalizedScore === null) {
                return null;
            }

            return 1 - abs($normalizedScore - $scoreToCompareTo);
        });
    }

    public function get(): Collection
    {
        $scores = [];

        $this->specifications()->all()->map(function (AttributeScore $attributeScore) {
            return $this->getMatchingScoreByAttributeScore($attributeScore);
        })->each(function ($matchingScoreByAttributeScore) use (&$scores) {
            $matchingScoreByAttributeScore->each(function ($score, $productKey) use (&$scores) {
                if (!isset($scores[$productKey])) {
                    $scores[$productKey] = 0;
                }

                $scores[$productKey] += $score;
            });
        });

        return $this->getCandidates()->map(function (CanBeSpecified $candidate, $productKey) use ($scores) {
            $score = $scores[$productKey];

            return compact('candidate', 'score');
        })->sortByDesc('score')->map(function ($candidateWithScore): CanBeSpecified {
            return $candidateWithScore['candidate'];
        })->values();
    }
}
