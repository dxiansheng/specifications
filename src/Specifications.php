<?php

namespace Pbmedia\ScoreMatcher;

use Countable;
use Illuminate\Support\Collection;
use Pbmedia\ScoreMatcher\AttributeScore;
use Pbmedia\ScoreMatcher\Interfaces\Attribute;
use Pbmedia\ScoreMatcher\Interfaces\Score;
use Pbmedia\ScoreMatcher\Interfaces\Specifications as SpecificationsInterface;

class Specifications implements SpecificationsInterface, Countable
{
    protected $specifications;

    public function __construct()
    {
        $this->specifications = new Collection;
    }

    public function add(AttributeScore $attributeScore): SpecificationsInterface
    {
        $key = $attributeScore->getAttribute()->getIdentifier();

        $this->specifications[$key] = $attributeScore;

        return $this;
    }

    public function addMany(array $attributeScores = []): SpecificationsInterface
    {
        Collection::make($attributeScores)->each(function (AttributeScore $attributeScore) {
            $this->add($attributeScore);
        });

        return $this;
    }

    public function set(Attribute $attribute, Score $score): SpecificationsInterface
    {
        $this->add(new AttributeScore($attribute, $score));

        return $this;
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

    public function forget(Attribute $attribute): SpecificationsInterface
    {
        $key = $attribute->getIdentifier();

        $this->specifications->forget($key);

        return $this;
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
