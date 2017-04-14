<?php

namespace TreeSoft\Specifications\DAL\Eloquent\Appliers;

use Illuminate\Database\Eloquent\Builder;
use TreeSoft\Specifications\Core\Specifications\Filters\IdSpecification;
use TreeSoft\Specifications\Core\Specifications\SpecificationInterface;

/**
 * Class IdSpecificationApplier.
 */
class IdSpecificationApplier extends AbstractApplier
{
    /**
     * @param Builder $query
     * @param SpecificationInterface|IdSpecification $specification
     *
     * @return Builder
     */
    public function apply(Builder $query, SpecificationInterface $specification): Builder
    {
        $model = $query->getModel();

        return $query->where(implode('.', [$model->getTable(), $model->getKeyName()]), $specification->getId());
    }
}
