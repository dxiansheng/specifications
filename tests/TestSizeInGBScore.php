<?php

namespace Pbmedia\ScoreMatcher;

use Pbmedia\ScoreMatcher\Interfaces\Score;

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
