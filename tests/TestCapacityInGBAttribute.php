<?php

namespace Pbmedia\Specifications;

use Pbmedia\Specifications\Interfaces\Attribute;

class TestCapacityInGBAttribute implements Attribute
{
    public function getIdentifier()
    {
        return 'CapacityInGB';
    }
}
