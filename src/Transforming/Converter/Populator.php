<?php

namespace Mildberry\Specifications\Transforming\Converter;

use Mildberry\Specifications\Transforming\Converter\Resolvers\Populator\ObjectResolver;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class Populator extends Converter
{
    /**
     * @return array
     */
    public function getInternalResolvers(): array
    {
        return array_merge(parent::getInternalResolvers(), [
            'object' => [
                'class' => ObjectResolver::class,
            ],
        ]);
    }
}
