<?php

namespace Pbmedia\ScoreMatcher;

use Pbmedia\ScoreMatcher\TestSizeInGBScore;

class ScoreTest extends \PHPUnit_Framework_TestCase
{
    public function testScoreValue()
    {
        $score = new TestSizeInGBScore(512);

        $this->assertEquals(512, $score->getValue());
    }
}
