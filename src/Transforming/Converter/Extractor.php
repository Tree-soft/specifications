<?php

namespace TreeSoft\Specifications\Transforming\Converter;

use TreeSoft\Specifications\Schema\LaravelFactory;
use TreeSoft\Specifications\Transforming\Converter\Resolvers\Extractor\DateTimeResolver;
use TreeSoft\Specifications\Transforming\Converter\Resolvers\Extractor\ObjectResolver;

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

    /**
     * Populator constructor.
     *
     * @param LaravelFactory $factory
     */
    public function __construct(LaravelFactory $factory)
    {
        parent::__construct($factory);

        $this
            ->registerResolver('datetime', ['class' => DateTimeResolver::class], 'simple');
    }
}
