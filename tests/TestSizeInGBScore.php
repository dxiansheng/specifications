<?php

namespace Pbmedia\Specifications;

use Pbmedia\Specifications\Interfaces\Score;

class TestSizeInGBScore implements Score
{
    private $sizeInGB;

    public function __construct($sizeInGB)
    {
        $this->sizeInGB = $sizeInGB;
    }

    public function getValue()
    {
        return $this->sizeInGB;
    }
}
