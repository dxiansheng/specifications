<?php

namespace Pbmedia\ScoreMatcher;

use Pbmedia\ScoreMatcher\Interfaces\Attribute;
use Pbmedia\ScoreMatcher\Interfaces\CanBeSpecified;
use Pbmedia\ScoreMatcher\Specifications;

class Matcher implements CanBeSpecified
{
    use HasSpecifications;

    protected $candidates = [];

    protected $criteria;

    public function __construct()
    {
        $this->criteria = new Specifications;
    }

    public function criteria(): Specifications
    {
        return $this->criteria;
    }

    public function addCandidate(CanBeSpecified $candidate)
    {
        $this->candidates[] = $candidate;
    }

    public function getCandidates(): array
    {
        return $this->candidates;
    }

    public function getScoresByAttribute(Attribute $attribute): array
    {
        return array_map(function ($candidate) use ($attribute) {
            $attributeScore = $candidate->specifications()->get($attribute);

            return $attributeScore ? $attributeScore->getScoreValue() : null;
        }, $this->candidates);
    }

    public function getNormalizedScoresByAttribute(Attribute $attribute): array
    {
        $scores = $this->getScoresByAttribute($attribute);

        $max = max($scores);

        return array_map(function ($score) use ($max) {
            return is_null($score) ? null : ($score / $max);
        }, $scores);
    }

    public function getMatchingScoreByAttributeScore(AttributeScore $attributeScore): array
    {
        $attribute  = $attributeScore->getAttribute();
        $scoreValue = $attributeScore->getScoreValue();

        $scores = $this->getScoresByAttribute($attribute);

        $normalizedScores = $this->getNormalizedScoresByAttribute($attribute);

        $scoreToCompareTo = $scoreValue / max($scores);

        return array_map(function ($normalizedScore) use ($scoreToCompareTo) {
            if ($normalizedScore === null) {
                return null;
            }

            return 1 - abs($normalizedScore - $scoreToCompareTo);
        }, $normalizedScores);
    }

    public function get(): array
    {
        $scores = [];

        foreach ($this->criteria()->getAll() as $attributeScore) {
            foreach ($this->getMatchingScoreByAttributeScore($attributeScore) as $key => $score) {
                if (!isset($scores[$key])) {
                    $scores[$key] = 0;
                }

                $scores[$key] += $score;
            }
        }

        $candidates = $this->candidates;

        array_walk($candidates, function (&$candidate, $key) use ($scores) {
            $score = $scores[$key];

            $candidate = compact('candidate', 'score');
        });

        usort($candidates, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return array_map(function ($candidate) {
            return $candidate['candidate'];
        }, $candidates);
    }
}
