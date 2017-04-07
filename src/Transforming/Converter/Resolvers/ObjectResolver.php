<?php

namespace TreeSoft\Specifications\Transforming\Converter\Resolvers;

use TreeSoft\Specifications\Exceptions\PopulatorException;
use TreeSoft\Specifications\Exceptions\PopulatorObjectException;
use TreeSoft\Specifications\Transforming\Fillers\FillerInterface;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class ObjectResolver extends AbstractResolver
{
    /**
     * @param mixed $data
     * @param callable $next
     *
     * @return mixed
     */
    public function resolve($data, $next)
    {
        return ($this->isObject($data)) ?
            ($this->getObject($data)) : ($next($data));
    }

    /**
     * @param mixed $data
     *
     * @return bool
     */
    abstract public function isObject($data): bool;

    /**
     * @param mixed $data
     *
     * @throws PopulatorObjectException
     *
     * @return mixed
     */
    public function getObject($data)
    {
        $converter = $this->getConverter();

        $schema = $this->getSchema();

        $entity = $this->createEntity($schema, $data);

        $filler = $this->createFiller();

        foreach ((array) $schema->properties as $property => $propertySchema) {
            try {
                $value = $converter->convert(
                    $this->getValue($data, $property), $propertySchema
                );

                $filler->fill($entity, $property, $value);
            } catch (PopulatorObjectException $e) {
                $e
                    ->setField(implode('.', [$property, $e->getField()]))
                    ->setData($data);

                throw $e;
            } catch (PopulatorException $e) {
                $e = new PopulatorObjectException('Cannot populate object', $e->getCode(), $e);

                $e
                    ->setField($property)
                    ->setData($data);

                throw $e;
            }
        }

        return $entity;
    }

    /**
     * @param object $schema
     * @param mixed $data
     *
     * @return mixed
     */
    abstract public function createEntity($schema, $data);

    /**
     * @param mixed $data
     * @param string $property
     *
     * @return mixed
     */
    abstract public function getValue($data, string $property);

    /**
     * @return FillerInterface
     */
    abstract public function createFiller(): FillerInterface;
}
