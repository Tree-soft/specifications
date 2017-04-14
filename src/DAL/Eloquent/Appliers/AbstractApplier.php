<?php

namespace TreeSoft\Specifications\DAL\Eloquent\Appliers;

use Illuminate\Database\Eloquent\Builder;
use TreeSoft\Specifications\Core\Specifications\SpecificationInterface;

/**
 * Class AbstractApplier.
 */
abstract class AbstractApplier
{
    /**
     * @param Builder $query
     * @param SpecificationInterface $specification
     *
     * @return Builder
     */
    abstract public function apply(Builder $query, SpecificationInterface $specification): Builder;
}
