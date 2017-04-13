<?php

namespace TreeSoft\Specifications\DAL\Eloquent\Transformers;

use TreeSoft\Specifications\DAL\Eloquent\Model;

/**
 * Interface TransformerBuilderInterface.
 */
interface TransformerBuilderInterface
{
    /**
     * @param Model $model
     *
     * @return string
     */
    public function getClass(Model $model): string;
}
