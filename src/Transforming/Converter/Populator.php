<?php

namespace TreeSoft\Specifications\Transforming\Converter;

use TreeSoft\Specifications\Schema\LaravelFactory;
use TreeSoft\Specifications\Transforming\Converter\Resolvers\Populator\DateTimeResolver;
use TreeSoft\Specifications\Transforming\Converter\Resolvers\Populator\ObjectResolver;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class Populator extends Converter
{
    /**
     * Populator constructor.
     *
     * @param LaravelFactory $factory
     */
    public function __construct(LaravelFactory $factory)
    {
        parent::__construct($factory);

        $this
            ->registerResolver('object', ['class' => ObjectResolver::class], 'complex')
            ->registerResolver('datetime', ['class' => DateTimeResolver::class], 'simple');
    }
}
