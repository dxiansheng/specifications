<?php

namespace Pbmedia\ScoreMatcher;

use Pbmedia\ScoreMatcher\Matcher;
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

        $normalSSD = new TestSSD;
        $normalSSD->specifications()->set(
            new TestCapacityInGBAttribute,
            new TestSizeInGBScore(256)
        );

        $bigSSD = new TestSSD;
        $bigSSD->specifications()->set(
            new TestCapacityInGBAttribute,
            new TestSizeInGBScore(512)
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

        $this->assertEquals([
            128 / 896,
            256 / 896,
            512 / 896,
        ], $scores);
    }
}
