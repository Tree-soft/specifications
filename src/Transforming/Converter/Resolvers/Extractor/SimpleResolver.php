<?php

namespace Mildberry\Specifications\Transforming\Converter\Resolvers\Extractor;

use Mildberry\Specifications\Transforming\Converter\Resolvers\Traits\SimpleResolverTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class SimpleResolver extends ExtractorResolver
{
    use SimpleResolverTrait;

    /**
     * @param $property
     *
     * @return mixed
     */
    public function getValue($property)
    {
        $method = $this->getMethod($property);

        return $this->data->{$method}();
    }
}
