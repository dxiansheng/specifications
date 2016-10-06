<?php

namespace Pbmedia\ScoreMatcher;

use Pbmedia\ScoreMatcher\Interfaces\Attribute;
use Pbmedia\ScoreMatcher\Interfaces\CanBeSpecified;
use Phpml\Preprocessing\Normalizer;

class Matcher
{
    protected $candidates = [];

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
        $scores = [$this->getScoresByAttribute($attribute)];

        $normalizer = new Normalizer(Normalizer::NORM_L1);
        $normalizer->transform($scores);

        return $scores[0];
    }
}
