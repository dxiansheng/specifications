<?php

namespace Pbmedia\ScoreMatcher;

use Pbmedia\ScoreMatcher\TestCapacityInGBAttribute;
use Pbmedia\ScoreMatcher\TestSizeInGBScore;

class AttributeScoreTest extends \PHPUnit_Framework_TestCase
{
    public function testAddAttributeScore()
    {
        $attributeScore = new AttributeScore(
            $attribute = new TestCapacityInGBAttribute,
            $score = new TestSizeInGBScore(128)
        );

        $this->assertEquals($attribute, $attributeScore->getAttribute());
        $this->assertEquals($score, $attributeScore->getScore());
        $this->assertEquals(128, $attributeScore->getScoreValue());
    }
}
