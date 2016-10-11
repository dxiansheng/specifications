<?php

namespace Pbmedia\Specifications;

use Pbmedia\Specifications\Interfaces\Specifications as SpecificationsInterface;

trait HasSpecifications
{
    /**
     * Instance of Specifications.
     *
     * @var \Pbmedia\Specifications\Interfaces\Specifications
     */
    protected $specifications;

    /**
     * Creates a new Specifications object if non exists
     * and returns it.
     *
     * @return \Pbmedia\Specifications\Interfaces\Specifications
     */
    public function specifications(): SpecificationsInterface
    {
        if (!$this->specifications) {
            $this->specifications = new Specifications;
        }

        return $this->specifications;
    }
}
