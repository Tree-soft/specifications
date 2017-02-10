<?php

namespace Mildberry\Specifications\DAL\Transformers;

use Mildberry\Specifications\DAL\EloquentMapper\Model;
use Mildberry\Specifications\Support\Transformers\EntityTransformer as ParentTransformer;
use Mildberry\Specifications\Transforming\Converter\Extractor;
use Mildberry\Specifications\Transforming\Converter\Populator;

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

        $data = $transformer->transform((object) $model->toArray());

        /**
         * @var Populator $populator
         */
        $populator = $this->container->make(Populator::class);

        $populator
            ->setNamespace($this->namespace);

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
