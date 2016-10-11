<?php

namespace Pbmedia\Specifications\Interfaces;

use Illuminate\Support\Collection;
use Pbmedia\Specifications\AttributeScore;
use Pbmedia\Specifications\Interfaces\Attribute;
use Pbmedia\Specifications\Interfaces\Score;

interface Specifications
{
    public function add(AttributeScore $attributeScore): Specifications;

    public function addMany(array $attributeScores = []): Specifications;

    public function set(Attribute $attribute, Score $score): Specifications;

    public function has(Attribute $attribute): bool;

    public function get(Attribute $attribute): AttributeScore;

    public function forget(Attribute $attribute): Specifications;

    public function all(): Collection;

    public function count(): int;
}
