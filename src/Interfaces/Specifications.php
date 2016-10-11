<?php

namespace Pbmedia\Specifications\Interfaces;

use Illuminate\Support\Collection;
use Pbmedia\Specifications\AttributeScore;
use Pbmedia\Specifications\Interfaces\Attribute;
use Pbmedia\Specifications\Interfaces\Score;

interface Specifications
{
    /**
     * Add an AttributeScore object.
     *
     * @param  \Pbmedia\Specifications\AttributeScore   $attributeScore
     * @return $this
     */
    public function add(AttributeScore $attributeScore): Specifications;

    /**
     * Helper method to add multiple AttributeScore objects at once.
     *
     * @param  array   $attributeScores
     * @return $this
     */
    public function addMany(array $attributeScores = []): Specifications;

    /**
     * Does the same as the 'add' method, but generates the AttributeScore object
     * automatically based on the given Attribute and Score objects
     *
     * @param  \Pbmedia\Specifications\Interfaces\Attribute   $attribute
     * @param  \Pbmedia\Specifications\Interfaces\Score   $score
     * @return $this
     */
    public function set(Attribute $attribute, Score $score): Specifications;

    /**
     * Returns a boolean wether the given Attribute is present.
     *
     * @param  \Pbmedia\Specifications\Interfaces\Attribute   $attribute
     * @return bool
     */
    public function has(Attribute $attribute): bool;

    /**
     * Returns the AttributeScore object based on the given Attribute object.
     *
     * @param  \Pbmedia\Specifications\Interfaces\Attribute   $attribute
     * @return \Pbmedia\Specifications\AttributeScore
     */
    public function get(Attribute $attribute): AttributeScore;

    /**
     * Forgets the AttributeScore object based on the given Attribute object.
     *
     * @param  \Pbmedia\Specifications\Interfaces\Attribute   $attribute
     * @return $this
     */
    public function forget(Attribute $attribute): Specifications;

    /**
     * Returns a Collection object containing all AttributeScore objects.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all(): Collection;

    /**
     * Count the number of AttributeScore objects.
     *
     * @return int
     */
    public function count(): int;
}
