<?php

namespace Pbmedia\Specifications;

use Pbmedia\Specifications\Interfaces\Attribute;
use Pbmedia\Specifications\Interfaces\Score;

class AttributeScore
{
    protected $attribute;

    protected $score;

    public function __construct(Attribute $attribute, Score $score)
    {
        $this->attribute = $attribute;

        $this->score = $score;
    }

    public function getAttribute(): Attribute
    {
        return $this->attribute;
    }

    public function getScore(): Score
    {
        return $this->score;
    }

    public function getScoreValue()
    {
        return $this->getScore()->getValue();
    }
}
