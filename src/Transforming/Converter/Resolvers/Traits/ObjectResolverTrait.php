<?php

namespace Mildberry\Specifications\Transforming\Converter\Resolvers\Traits;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
trait ObjectResolverTrait
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
        $schema = $this->getSchema()->properties->{$property};

        return $schema->type == 'object';
    }

    /**
     * @param string $property
     *
     * @return mixed
     */
    abstract public function getValue(string $property);

    /**
     * @return object
     */
    abstract public function getSchema();
}
