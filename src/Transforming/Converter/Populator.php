<?php

namespace Mildberry\Specifications\Transforming\Converter;

use Mildberry\Specifications\Schema\LaravelFactory;
use Mildberry\Specifications\Transforming\Converter\Resolvers\Populator\DateTimeResolver;
use Mildberry\Specifications\Transforming\Converter\Resolvers\Populator\ObjectResolver;

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
