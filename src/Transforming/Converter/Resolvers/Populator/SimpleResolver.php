<?php

namespace Mildberry\Specifications\Transforming\Converter\Resolvers\Populator;

use Mildberry\Specifications\Transforming\Converter\Resolvers\AbstractResolver;
use Mildberry\Specifications\Transforming\Converter\Resolvers\Traits\SimpleResolverTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class SimpleResolver extends AbstractResolver
{
    use SimpleResolverTrait;

    /**
     * @param $property
     *
     * @return mixed
     */
    public function getValue($property)
    {
        return $this->data->{$property};
    }
}
