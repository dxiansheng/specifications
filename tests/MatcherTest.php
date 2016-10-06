<?php

namespace Pbmedia\ScoreMatcher;

use Pbmedia\ScoreMatcher\Matcher;
use Pbmedia\ScoreMatcher\TestCacheInGBAttribute;
use Pbmedia\ScoreMatcher\TestCapacityInGBAttribute;
use Pbmedia\ScoreMatcher\TestSizeInGBScore;
use Pbmedia\ScoreMatcher\TestSSD;

class MatcherTest extends \PHPUnit_Framework_TestCase
{
    private function getMatcherWithThreeSSDs()
    {
        $smallSSD = new TestSSD;

        $smallSSD->specifications()->set(
            new TestCapacityInGBAttribute,
            new TestSizeInGBScore(128)
        );

        $smallSSD->specifications()->set(
            new TestCacheInGBAttribute,
            new TestSizeInGBScore(0.5)
        );

        $normalSSD = new TestSSD;

        $normalSSD->specifications()->set(
            new TestCapacityInGBAttribute,
            new TestSizeInGBScore(256)
        );

        $normalSSD->specifications()->set(
            new TestCacheInGBAttribute,
            new TestSizeInGBScore(0.5)
        );

        $bigSSD = new TestSSD;

        $bigSSD->specifications()->set(
            new TestCapacityInGBAttribute,
            new TestSizeInGBScore(512)
        );

        $bigSSD->specifications()->set(
            new TestCacheInGBAttribute,
            new TestSizeInGBScore(1.5)
        );

        $matcher = new Matcher;

        $matcher->addCandidate($smallSSD);
        $matcher->addCandidate($normalSSD);
        $matcher->addCandidate($bigSSD);

        return $matcher;
    }

    public function testAddingCandidates()
    {
        $matcher = new Matcher;
        $this->assertEmpty($matcher->getCandidates());

        $matcher = $this->getMatcherWithThreeSSDs();
        $this->assertCount(3, $matcher->getCandidates());
    }

    public function testScoresByAttribute()
    {
        $matcher = $this->getMatcherWithThreeSSDs();

        $scores = $matcher->getScoresByAttribute(new TestCapacityInGBAttribute);

        $this->assertEquals([128, 256, 512], $scores);
    }

    public function testNormalizedScoresByAttribute()
    {
        $matcher = $this->getMatcherWithThreeSSDs();

        $scores = $matcher->getNormalizedScoresByAttribute(new TestCapacityInGBAttribute);

        $this->assertEquals([0.25, 0.5, 1], $scores);
    }

    public function testGetMatchingScoreOf256CapacitySSD()
    {
        $matcher = $this->getMatcherWithThreeSSDs();

        $scoreAttribute = new AttributeScore(
            new TestCapacityInGBAttribute,
            new TestSizeInGBScore(256)
        );

        $matchingScore = $matcher->getMatchingScoreByAttributeScore($scoreAttribute);

        $this->assertEquals([0.75, 1, 0.5], $matchingScore);
    }

    public function testGetDisksSortedByMatch()
    {
        $matcher = $this->getMatcherWithThreeSSDs();

        $matcher->criteria()->add(new AttributeScore(
            new TestCapacityInGBAttribute,
            new TestSizeInGBScore(384)
        ));

        $matcher->criteria()->add(new AttributeScore(
            new TestCacheInGBAttribute,
            new TestSizeInGBScore(1.25)
        ));
    }
}
