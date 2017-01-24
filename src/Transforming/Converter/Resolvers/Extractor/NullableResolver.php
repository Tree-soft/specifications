<?php

namespace Mildberry\Specifications\Transforming\Converter\Resolvers\Extractor;

use Mildberry\Specifications\Transforming\Converter\Resolvers\Traits\NullableResolverTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class NullableResolver extends ExtractorResolver
{
    use NullableResolverTrait;

    /**
     * @param string $property
     *
     * @return bool
     */
    public function isNull(string $property): bool
    {
        $method = $this->getMethod($property);

        $value = $this->data->{$method}();

        return isset($value);
    }
}
