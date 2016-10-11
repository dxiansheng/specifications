<?php

namespace Pbmedia\Specifications;

use Pbmedia\Specifications\TestSizeInGBScore;

class ScoreTest extends \PHPUnit_Framework_TestCase
{
    public function testScoreValue()
    {
        $score = new TestSizeInGBScore(512);

        $this->assertEquals(512, $score->getValue());
    }
}
