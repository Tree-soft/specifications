<?php

namespace Mildberry\Specifications\Transforming\Converter\Resolvers\Traits;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
trait NullableResolverTrait
{
    /**
     * @param string $property
     * @param callable $next
     *
     * @return mixed
     */
    public function resolve(string $property, $next)
    {
        return ($this->isNull($property)) ? ($next($property)) : (null);
    }

    /**
     * @param string $property
     *
     * @return bool
     */
    abstract public function isNull(string $property): bool;
}
