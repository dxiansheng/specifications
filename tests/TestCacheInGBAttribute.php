<?php

namespace Pbmedia\Specifications;

use Pbmedia\Specifications\Interfaces\Attribute;

class TestCacheInGBAttribute implements Attribute
{
    public function getIdentifier()
    {
        return 'CacheInGB';
    }
}
