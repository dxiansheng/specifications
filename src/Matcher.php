<?php

namespace Pbmedia\Specifications;

use Illuminate\Support\Collection;
use Pbmedia\Specifications\Interfaces\Attribute;
use Pbmedia\Specifications\Interfaces\CanBeSpecified;
use Pbmedia\Specifications\Specifications;

class Matcher implements CanBeSpecified
{
    use HasSpecifications;

    protected $candidates;

    public function __construct()
    {
        $this->candidates = new Collection;
    }

    public function addCandidate(CanBeSpecified $candidate): Matcher
    {
        $this->candidates->push($candidate);

        return $this;
    }

    public function addCandidates($candidates): Matcher
    {
        $candidates = is_array($candidates) ? $candidates : func_get_args();

        Collection::make($candidates)->each(function ($candidate) {
            $this->addCandidate($candidate);
        });

        return $this;
    }

    public function getCandidates(): Collection
    {
        return $this->candidates;
    }

    public function getScoresByAttribute(Attribute $attribute): Collection
    {
        return $this->candidates->map(function ($candidate) {
            return $candidate->specifications();
        })->map(function ($specifications) use ($attribute) {
            if (!$specifications->has($attribute)) {
                return;
            }

            return $specifications->get($attribute)->getScoreValue();
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

        if (!$scores->max()) {
            return $this->candidates->map(function () {
                return 0;
            });
        }

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
        if ($this->specifications()->all()->isEmpty()) {
            return $this->getCandidates();
        }

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
