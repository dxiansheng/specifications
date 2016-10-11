<?php

namespace Pbmedia\Specifications\Interfaces;

interface Attribute
{
    /**
     * Returns a value by which we can identify this object.
     *
     * @return mixed
     */
    public function getIdentifier();
}
