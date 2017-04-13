<?php

namespace TreeSoft\Specifications\DAL\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class QueryBuilder.
 */
class QueryBuilder
{
    /**
     * @param Model $model
     * @param mixed $expression
     *
     * @return Builder
     */
    public function build(Model $model, $expression): Builder
    {
        $query = $model->newQuery();

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
