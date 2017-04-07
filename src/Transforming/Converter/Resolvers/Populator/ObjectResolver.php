<?php

namespace TreeSoft\Specifications\Transforming\Converter\Resolvers\Populator;

use TreeSoft\Specifications\Generators\TypeExtractor;
use TreeSoft\Specifications\Transforming\Converter\Converter;
use TreeSoft\Specifications\Transforming\Fillers\FillerInterface;
use TreeSoft\Specifications\Transforming\Fillers\SetterFiller;
use TreeSoft\Specifications\Transforming\Converter\Populator;
use TreeSoft\Specifications\Transforming\Converter\Resolvers\ObjectResolver as ParentObjectResolver;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ObjectResolver extends ParentObjectResolver
{
    /**
     * @param object $schema
     * @param mixed $data
     *
     * @return mixed
     */
    public function createEntity($schema, $data)
    {
        /**
         * @var TypeExtractor $typeExtractor
         */
        $typeExtractor = $this->container->make(TypeExtractor::class);

        $types = $typeExtractor->extract($schema);

        assert(!is_array($types), 'Polymorphic types are not implemented.');

        $class = $types;

        return new $class();
    }

    /**
     * @param mixed $data
     * @param string $property
     *
     * @return mixed
     */
    public function getValue($data, string $property)
    {
        return $data->{$property} ?? null;
    }

    /**
     * @return FillerInterface|SetterFiller
     */
    public function createFiller(): FillerInterface
    {
        return $this->container->make(SetterFiller::class);
    }

    /**
     * @param mixed $data
     *
     * @return bool
     */
    public function isObject($data): bool
    {
        $schema = $this->getSchema();

        return is_object($data) && isset($schema->type) && ($schema->type == 'object');
    }

    /**
     * @return Converter|Populator
     */
    public function createConverter(): Converter
    {
        return $this->container->make(Populator::class);
    }
}
