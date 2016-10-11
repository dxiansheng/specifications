<?php

namespace Pbmedia\Specifications;

use Pbmedia\Specifications\Interfaces\Attribute;
use Pbmedia\Specifications\Interfaces\Score;

class AttributeScore
{
    /**
     * Instance of Attribute.
     *
     * @var \Pbmedia\Specifications\Interfaces\Attribute
     */
    protected $attribute;

    /**
     * Instance of Score.
     *
     * @var \Pbmedia\Specifications\Interfaces\Score
     */
    protected $score;

    /**
     * Create a new AttributeScore instance.
     *
     * @param  \Pbmedia\Specifications\Interfaces\Attribute   $attribute
     * @param  \Pbmedia\Specifications\Interfaces\Score   $score
     * @return void
     */
    public function __construct(Attribute $attribute, Score $score)
    {
        $this->attribute = $attribute;
        $this->score     = $score;
    }

    /**
     * Get the Attribute object.
     *
     * @return \Pbmedia\Specifications\Interfaces\Attribute
     */
    public function getAttribute(): Attribute
    {
        return $this->attribute;
    }

    /**
     * Get the Score object.
     *
     * @return \Pbmedia\Specifications\Interfaces\Score
     */
    public function getScore(): Score
    {
        return $this->score;
    }

    /**
     * Get the value of the Score object.
     *
     * @return mixed
     */
    public function getScoreValue()
    {
        return $this->getScore()->getValue();
    }
}
