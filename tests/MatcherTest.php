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

        $this->assertEquals([128, 256, 512], $scores->toArray());
    }

    public function testNormalizedScoresByAttribute()
    {
        $matcher = $this->getMatcherWithThreeSSDs();

        $scores = $matcher->getNormalizedScoresByAttribute(new TestCapacityInGBAttribute);

        $this->assertEquals([0.25, 0.5, 1], $scores->toArray());
    }

    public function testGetMatchingScoreOf256CapacitySSD()
    {
        $matcher = $this->getMatcherWithThreeSSDs();

        $scoreAttribute = new AttributeScore(
            new TestCapacityInGBAttribute,
            new TestSizeInGBScore(256)
        );

        $matchingScore = $matcher->getMatchingScoreByAttributeScore($scoreAttribute);

        $this->assertEquals([0.75, 1, 0.5], $matchingScore->toArray());
    }

    public function testGetDisksSortedByMatch()
    {
        $matcher = $this->getMatcherWithThreeSSDs();

        $matcher->specifications()->add(new AttributeScore(
            new TestCapacityInGBAttribute,
            new TestSizeInGBScore(384)
        ));

        $matcher->specifications()->add(new AttributeScore(
            new TestCacheInGBAttribute,
            new TestSizeInGBScore(1.25)
        ));

        $disks = $matcher->get();

        $this->assertEquals(512, $disks->get(0)->specifications()->get(new TestCapacityInGBAttribute)->getScoreValue());
        $this->assertEquals(256, $disks->get(1)->specifications()->get(new TestCapacityInGBAttribute)->getScoreValue());
        $this->assertEquals(128, $disks->get(2)->specifications()->get(new TestCapacityInGBAttribute)->getScoreValue());
    }

    public function testWithoutScores()
    {
        $unknownSSD = new TestSSD;
        $normalSSD  = new TestSSD;

        $matcher = new Matcher;
        $matcher->addCandidates($unknownSSD, $normalSSD);
        $matcher->specifications()->set(new TestCapacityInGBAttribute, new TestSizeInGBScore(64));

        $disks = $matcher->get();

        $this->assertCount(2, $disks);
        $this->assertEquals($unknownSSD, $disks->get(0));
        $this->assertEquals($normalSSD, $disks->get(1));
    }

    public function testWithoutSpecifications()
    {
        $matcher = new Matcher;
        $matcher->addCandidates($disks = [new TestSSD, new TestSSD, new TestSSD]);

        $this->assertEquals($disks, $matcher->get()->toArray());
    }
}
