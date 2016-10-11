<?php

namespace Pbmedia\Specifications;

use Countable;
use Illuminate\Support\Collection;
use Pbmedia\Specifications\AttributeScore;
use Pbmedia\Specifications\Interfaces\Attribute;
use Pbmedia\Specifications\Interfaces\Score;
use Pbmedia\Specifications\Interfaces\Specifications as SpecificationsInterface;

class Specifications implements SpecificationsInterface, Countable
{
    /**
     * Collection instance.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $specifications;

    /**
     * Create a new Specifications instance and instantiates a new Collection.
     *
     * @return void
     */
    public function __construct()
    {
        $this->specifications = new Collection;
    }

    /**
     * {@inheritDoc}
     */
    public function add(AttributeScore $attributeScore): SpecificationsInterface
    {
        $key = $attributeScore->getAttribute()->getIdentifier();

        $this->specifications[$key] = $attributeScore;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function addMany(array $attributeScores = []): SpecificationsInterface
    {
        Collection::make($attributeScores)->each(function (AttributeScore $attributeScore) {
            $this->add($attributeScore);
        });

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function set(Attribute $attribute, Score $score): SpecificationsInterface
    {
        $this->add(new AttributeScore($attribute, $score));

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function has(Attribute $attribute): bool
    {
        $key = $attribute->getIdentifier();

        return $this->specifications->has($key);
    }

    /**
     * {@inheritDoc}
     */
    public function get(Attribute $attribute): AttributeScore
    {
        $key = $attribute->getIdentifier();

        return $this->specifications->get($key);
    }

    /**
     * {@inheritDoc}
     */
    public function forget(Attribute $attribute): SpecificationsInterface
    {
        $key = $attribute->getIdentifier();

        $this->specifications->forget($key);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function all(): Collection
    {
        return $this->specifications;
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return $this->specifications->count();
    }
}
