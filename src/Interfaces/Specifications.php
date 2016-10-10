<?php

namespace Pbmedia\ScoreMatcher\Interfaces;

use Illuminate\Support\Collection;
use Pbmedia\ScoreMatcher\AttributeScore;
use Pbmedia\ScoreMatcher\Interfaces\Attribute;
use Pbmedia\ScoreMatcher\Interfaces\Score;

interface Specifications
{
    public function add(AttributeScore $attributeScore);

    public function addMany(array $attributeScores = []);

    public function set(Attribute $attribute, Score $score);

    public function has(Attribute $attribute): bool;

    public function get(Attribute $attribute): AttributeScore;

    public function forget(Attribute $attribute);

    public function all(): Collection;

    public function count(): int;
}
