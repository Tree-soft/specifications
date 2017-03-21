<?php

namespace Mildberry\Specifications\Transforming\Converter\Resolvers\Extractor;

use Mildberry\Specifications\Generators\TypeExtractor;
use Mildberry\Specifications\Transforming\Converter\Converter;
use Mildberry\Specifications\Transforming\Converter\Extractor;
use Mildberry\Specifications\Transforming\Fillers\FillerInterface;
use Mildberry\Specifications\Transforming\Fillers\ObjectFiller;
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
        return (object) [];
    }

    /**
     * @param mixed $data
     * @param string $property
     *
     * @return mixed
     */
    public function getValue($data, string $property)
    {
        $method = $this->getMethod($property);

        return $data->{$method}();
    }

    /**
     * @return FillerInterface|ObjectFiller
     */
    public function createFiller(): FillerInterface
    {
        return $this->container->make(ObjectFiller::class);
    }

    /**
     * @param string $field
     *
     * @return string
     */
    public function getMethod(string $field): string
    {
        $property = ucfirst($field);

        return "get{$property}";
    }

    /**
     * @param mixed $data
     *
     * @return bool
     */
    public function isObject($data): bool
    {
        /**
         * @var TypeExtractor $extractor
         */
        $extractor = $this->container->make(TypeExtractor::class);

        $schema = $this->getSchema();

        $types = $extractor->extract($schema);

        if (!is_array($types)) {
            $types = [$types];
        }

        return is_object($data) && $this->checkType($data, $types);
    }

    /**
     * @param object $entity
     * @param array $types
     *
     * @return bool
     */
    protected function checkType($entity, array $types): bool
    {
        $type = array_first($types, function ($type) use ($entity) {
            return is_a($entity, $type);
        });

        return isset($type);
    }

    /**
     * @return Converter|Extractor
     */
    public function createConverter(): Converter
    {
        return $this->container->make(Extractor::class);
    }
}
