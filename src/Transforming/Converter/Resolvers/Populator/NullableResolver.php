<?php

namespace Mildberry\Specifications\Transforming\Converter\Resolvers\Populator;

use Mildberry\Specifications\Transforming\Converter\Resolvers\AbstractResolver;
use Mildberry\Specifications\Transforming\Converter\Resolvers\Traits\NullableResolverTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class NullableResolver extends AbstractResolver
{
    use NullableResolverTrait;

    /**
     * @param string $property
     *
     * @return bool
     */
    public function isNull(string $property): bool
    {
        return property_exists($this->data, $property);
    }
}
