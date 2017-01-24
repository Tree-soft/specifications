<?php

namespace Mildberry\Specifications\Transforming\Populator\Resolvers;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class NullableResolver extends AbstractResolver
{
    /**
     * @param string $property
     * @param callable $next
     *
     * @return mixed
     */
    public function resolve(string $property, $next)
    {
        return (property_exists($this->data, $property)) ? ($next($property)) : (null);
    }
}
