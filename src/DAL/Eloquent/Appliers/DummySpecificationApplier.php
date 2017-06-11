<?php

namespace TreeSoft\Specifications\DAL\Eloquent\Appliers;

use Illuminate\Database\Eloquent\Builder;
use TreeSoft\Specifications\Core\Specifications\SpecificationInterface;

/**
 * Class DummySpecificationApplier.
 */
class DummySpecificationApplier extends AbstractApplier
{
    /**
     * @param Builder $query
     * @param SpecificationInterface $specification
     *
     * @return Builder
     */
    public function apply(Builder $query, SpecificationInterface $specification): Builder
    {
        return $query;
    }
}
