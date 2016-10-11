<?php

namespace Pbmedia\Specifications;

use Pbmedia\Specifications\Specifications;
use Pbmedia\Specifications\TestCacheInGBAttribute;
use Pbmedia\Specifications\TestCapacityInGBAttribute;
use Pbmedia\Specifications\TestSizeInGBScore;

class SpecificationsTest extends \PHPUnit_Framework_TestCase
{
    public function testAddAttributeScore()
    {
        $attributeScore = new AttributeScore(
            $attribute = new TestCapacityInGBAttribute,
            new TestSizeInGBScore(128)
        );

        $specifications = new Specifications;
        $this->assertFalse($specifications->has($attribute));
        $this->assertCount(0, $specifications);

        $specifications->add($attributeScore);
        $this->assertTrue($specifications->has($attribute));
        $this->assertCount(1, $specifications);
    }

    public function testAddManyAttributeScores()
    {
        $specifications = new Specifications;

        $specifications->addMany([
            new AttributeScore($cacheAttribute = new TestCacheInGBAttribute, new TestSizeInGBScore(0.5)),
            new AttributeScore($capacityAttribute = new TestCapacityInGBAttribute, new TestSizeInGBScore(128)),
        ]);

        $this->assertTrue($specifications->has($cacheAttribute));
        $this->assertTrue($specifications->has($capacityAttribute));

        $this->assertCount(2, $specifications);
    }

    public function testSetAttributeScore()
    {
        $attribute = new TestCapacityInGBAttribute;
        $score     = new TestSizeInGBScore(128);

        $specifications = new Specifications;
        $specifications->set($attribute, $score);

        $this->assertTrue($specifications->has($attribute));
        $this->assertCount(1, $specifications);
    }

    public function testGetAttributeScore()
    {
        $attribute = new TestCapacityInGBAttribute;
        $score     = new TestSizeInGBScore(128);

        $specifications = new Specifications;
        $specifications->set($attribute, $score);

        $attributeScore = $specifications->get($attribute);

        $this->assertInstanceOf(AttributeScore::class, $attributeScore);
        $this->assertEquals($attribute, $attributeScore->getAttribute());
        $this->assertEquals($score, $attributeScore->getScore());
    }

    public function testTwoAttributeScores()
    {
        $cache    = new TestCacheInGBAttribute;
        $capacity = new TestCapacityInGBAttribute;

        $specifications = new Specifications;
        $specifications->set($cache, new TestSizeInGBScore(0.5));
        $specifications->set($capacity, new TestSizeInGBScore(128));

        $this->assertTrue($specifications->has($cache));
        $this->assertTrue($specifications->has($capacity));
        $this->assertCount(2, $specifications);
    }

    public function testOverwritingAttributeScore()
    {
        $capacity = new TestCapacityInGBAttribute;

        $specifications = new Specifications;
        $specifications->set($capacity, new TestSizeInGBScore(128));
        $this->assertEquals(128, $specifications->get($capacity)->getScoreValue());

        $specifications->set($capacity, new TestSizeInGBScore(256));
        $this->assertEquals(256, $specifications->get($capacity)->getScoreValue());
    }

    public function testForgettingAttributeScore()
    {
        $capacity = new TestCapacityInGBAttribute;

        $specifications = new Specifications;
        $specifications->set($capacity, new TestSizeInGBScore(128));
        $specifications->forget($capacity);

        $this->assertCount(0, $specifications);
    }
}
