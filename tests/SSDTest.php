<?php

namespace Pbmedia\Specifications;

use Pbmedia\Specifications\Specifications;
use Pbmedia\Specifications\TestSSD;

class SSDTest extends \PHPUnit_Framework_TestCase
{
    public function testSSDHasSpecifications()
    {
        $ssd = new TestSSD;

        $this->assertInstanceOf(Specifications::class, $ssd->specifications());
    }
}
