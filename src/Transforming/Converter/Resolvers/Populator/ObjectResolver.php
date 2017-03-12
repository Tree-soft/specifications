<?php

namespace Mildberry\Specifications\Transforming\Converter\Resolvers\Populator;

use Mildberry\Specifications\Generators\TypeExtractor;
use Mildberry\Specifications\Transforming\Converter\Converter;
use Mildberry\Specifications\Transforming\Converter\Fillers\FillerInterface;
use Mildberry\Specifications\Transforming\Converter\Fillers\SetterFiller;
use Mildberry\Specifications\Transforming\Converter\Populator;
use Mildberry\Specifications\Transforming\Converter\Resolvers\ObjectResolver as ParentObjectResolver;

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
        $typeExtractor = new TypeExtractor();

        $typeExtractor
            ->setNamespace($this->getConverter()->getNamespace());

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
        return is_object($data) && $this->getSchema()->type == 'object';
    }

    /**
     * @return Converter|Populator
     */
    public function createConverter(): Converter
    {
        return $this->container->make(Populator::class);
    }
}
