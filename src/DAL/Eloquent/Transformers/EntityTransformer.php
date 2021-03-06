<?php

namespace TreeSoft\Specifications\DAL\Eloquent\Transformers;

use TreeSoft\Specifications\DAL\Eloquent\Model;
use TreeSoft\Specifications\Support\DataPreparator;
use TreeSoft\Specifications\Support\Transformers\EntityTransformer as ParentTransformer;
use TreeSoft\Specifications\Transforming\Converter\Extractor;
use TreeSoft\Specifications\Transforming\Converter\Populator;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class EntityTransformer extends ParentTransformer
{
    /**
     * @param object $entity
     * @param Model $model
     *
     * @return Model
     */
    public function extract($entity, Model $model): Model
    {
        assert(isset($this->class));

        $schema = $this->getSchema($this->class);

        /**
         * @var Extractor $extractor
         */
        $extractor = $this->container->make(Extractor::class);

        $data = $extractor->convert($entity, $schema);

        $transformer = $this->factory->create($schema, $model->schema);

        $model->fill((array) $transformer->transform($data));

        return $model;
    }

    /**
     * @param Model $model
     * @param object $entity
     *
     * @return object
     */
    public function populate(Model $model, $entity = null)
    {
        assert(isset($this->class));

        $schema = $this->getSchema($this->class);

        $transformer = $this->factory->create($model->schema, $schema);

        /**
         * @var DataPreparator $preparator
         */
        $preparator = $this->container->make(DataPreparator::class);

        $data = $transformer->transform($preparator->prepare($model->toArray()));

        /**
         * @var Populator $populator
         */
        $populator = $this->container->make(Populator::class);

        return $populator->convert($this->mergeWithOriginal($data, $entity), $schema);
    }

    /**
     * @param mixed $data
     * @param object $entity
     *
     * @return mixed
     */
    public function mergeWithOriginal($data, $entity = null)
    {
        if (empty($entity)) {
            return $data;
        }

        $schema = $this->getSchema($this->class);

        /**
         * @var Extractor $extractor
         */
        $extractor = $this->container->make(Extractor::class);

        $originalData = $extractor->convert($entity, $schema);

        foreach ((array) $data as $key => $value) {
            $originalData->{$key} = $value;
        }

        return $originalData;
    }
}
