<?php

namespace Pbmedia\ScoreMatcher;

use Pbmedia\ScoreMatcher\Interfaces\Attribute;

class TestCacheInGBAttribute implements Attribute
{
    public function getIdentifier()
    {
        return 'CacheInGB';
    }
}
