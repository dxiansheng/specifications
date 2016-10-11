<?php

namespace Pbmedia\Specifications;

use Pbmedia\Specifications\Interfaces\Specifications as SpecificationsInterface;

trait HasSpecifications
{
    protected $specifications;

    public function specifications(): SpecificationsInterface
    {
        if (!$this->specifications) {
            $this->specifications = new Specifications;
        }

        return $this->specifications;
    }
}
