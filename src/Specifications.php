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
        $key = get_class($attributeScore->getAttribute());

        $this->specifications[$key] = $attributeScore;
    }

    public function set(Attribute $attribute, Score $score)
    {
        $this->add(new AttributeScore($attribute, $score));
    }

    public function has(Attribute $attribute): bool
    {
        $key = get_class($attribute);

        return isset($this->specifications[$key]);
    }

    public function get(Attribute $attribute): AttributeScore
    {
        $key = get_class($attribute);

        return $this->specifications[$key] ?? null;
    }

    public function count(): int
    {
        return count($this->specifications);
    }
}
