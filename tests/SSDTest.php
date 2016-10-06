<?php

namespace Pbmedia\ScoreMatcher;

use Pbmedia\ScoreMatcher\Specifications;
use Pbmedia\ScoreMatcher\TestSSD;

class SSDTest extends \PHPUnit_Framework_TestCase
{
    public function testSSDHasSpecifications()
    {
        $ssd = new TestSSD;

        $this->assertInstanceOf(Specifications::class, $ssd->specifications());
    }
}
