<?php

namespace Mildberry\Specifications\Transforming\Populator\Resolvers;

use Mildberry\Specifications\Transforming\Populator\Populator;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ObjectResolver extends AbstractResolver
{
    /**
     * @param string $property
     * @param callable $next
     *
     * @return mixed
     */
    public function resolve(string $property, $next)
    {
        return ($this->isObject($property)) ?
            ($this->getValue($property)) : ($next($property));
    }

    /**
     * @param string $property
     *
     * @return bool
     */
    public function isObject(string $property): bool
    {
        $schema = $this->schema->properties->{$property};

        return $schema->type == 'object';
    }

    /**
     * @param string $property
     *
     * @return mixed
     */
    public function getValue(string $property)
    {
        /**
         * @var Populator $populator
         */
        $populator = $this->container->make(Populator::class);

        $populator
            ->setNamespace($this->populator->getNamespace());

        return (property_exists($this->data, $property)) ?
            ($populator->populate(
                $this->data->{$property}, $this->schema->properties->{$property}
            )) : (null);
    }
}
