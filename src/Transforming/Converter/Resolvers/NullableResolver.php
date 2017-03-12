<?php

namespace Mildberry\Specifications\Transforming\Converter\Resolvers;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class NullableResolver extends AbstractResolver
{
    /**
     * @param mixed $data
     * @param callable $next
     *
     * @return mixed
     */
    public function resolve($data, $next)
    {
        return ($this->isNull($data)) ? (null) : ($next($data));
    }

    /**
     * @param $data
     *
     * @return bool
     *
     * @internal param string $property
     */
    public function isNull($data): bool
    {
        return is_null($data);
    }
}
