<?php

namespace Pbmedia\ScoreMatcher;

trait HasSpecifications
{
    protected $specifications;

    public function specifications()
    {
        if (!$this->specifications) {
            $this->specifications = new Specifications;
        }

        return $this->specifications;
    }
}
