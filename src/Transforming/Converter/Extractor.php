<?php

namespace Mildberry\Specifications\Transforming\Converter;

use Mildberry\Specifications\Transforming\Converter\Resolvers\Extractor\ObjectResolver;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class Extractor extends Converter
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
