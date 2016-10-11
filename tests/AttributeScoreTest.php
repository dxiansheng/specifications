<?php

namespace Pbmedia\Specifications;

use Pbmedia\Specifications\TestCapacityInGBAttribute;
use Pbmedia\Specifications\TestSizeInGBScore;

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
