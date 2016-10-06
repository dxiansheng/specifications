<?php

namespace Pbmedia\ScoreMatcher;

use Pbmedia\ScoreMatcher\Interfaces\Attribute;

class TestCapacityInGBAttribute implements Attribute
{
    public function getIdentifier()
    {
        return 'CapacityInGB';
    }
}
