<?php

namespace TreeSoft\Specifications\DAL\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;
use TreeSoft\Specifications\Core\Specifications\SpecificationInterface;
use TreeSoft\Specifications\DAL\Eloquent\Appliers\ApplierFactory;

/**
 * Class QueryBuilder.
 */
class QueryBuilder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @param Model $model
     * @param SpecificationInterface|null $specification
     *
     * @return Builder
     */
    public function build(Model $model, SpecificationInterface $specification = null): Builder
    {
        $query = $model->newQuery();

        if (isset($specification)) {
            /**
             * @var ApplierFactory $factory
             */
            $factory = $this->container->make(ApplierFactory::class);

            $applier = $factory->create($specification);

            $query = $applier->apply($query, $specification);
        }

        return $query;
    }

    /**
     * @param Model $model
     * @param $expression
     *
     * @return mixed
     */
    public function result(Model $model, $expression): Collection
    {
        $fields = ['*'];

        return $this->build($model, $expression)->get($fields);
    }
}
