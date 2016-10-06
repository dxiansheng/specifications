<?php

namespace Pbmedia\ScoreMatcher;

use Countable;
use Pbmedia\ScoreMatcher\AttributeScore;
use Pbmedia\ScoreMatcher\Interfaces\Attribute;
use Pbmedia\ScoreMatcher\Interfaces\Score;

class Specifications implements Countable
{
    protected $specifications = [];

    public function add(AttributeScore $attributeScore)
    {
        $key = $attributeScore->getAttribute()->getIdentifier();

        $this->specifications[$key] = $attributeScore;
    }

    public function set(Attribute $attribute, Score $score)
    {
        $this->add(new AttributeScore($attribute, $score));
    }

    public function has(Attribute $attribute): bool
    {
        $key = $attribute->getIdentifier();

        return isset($this->specifications[$key]);
    }

    public function get(Attribute $attribute): AttributeScore
    {
        $key = $attribute->getIdentifier();

        return $this->specifications[$key] ?? null;
    }

    public function getAll(): array
    {
        return $this->specifications;
    }

    public function count(): int
    {
        return count($this->specifications);
    }
}
