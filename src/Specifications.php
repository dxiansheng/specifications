<?php

namespace Pbmedia\ScoreMatcher;

use Countable;
use Illuminate\Support\Collection;
use Pbmedia\ScoreMatcher\AttributeScore;
use Pbmedia\ScoreMatcher\Interfaces\Attribute;
use Pbmedia\ScoreMatcher\Interfaces\Score;

class Specifications implements Countable
{
    protected $specifications;

    public function __construct()
    {
        $this->specifications = new Collection;
    }

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

        return $this->specifications->has($key);
    }

    public function get(Attribute $attribute): AttributeScore
    {
        $key = $attribute->getIdentifier();

        return $this->specifications->get($key);
    }

    public function all(): Collection
    {
        return $this->specifications;
    }

    public function count(): int
    {
        return $this->specifications->count();
    }
}
