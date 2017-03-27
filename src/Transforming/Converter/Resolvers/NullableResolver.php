<?php

namespace Mildberry\Specifications\Transforming\Converter\Resolvers;

use Mildberry\Specifications\Generators\TypeExtractor;

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
     */
    public function isNull($data): bool
    {
        return is_null($data) || (isset($this->schema->type) && ($this->schema->type == TypeExtractor::NULL));
    }
}
