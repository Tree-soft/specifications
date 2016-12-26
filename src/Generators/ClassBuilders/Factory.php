<?php

namespace Mildberry\Specifications\Generators\ClassBuilders;

use Mildberry\Specifications\Generators\AbstractBuilder;
use Mildberry\Specifications\Generators\BuilderFactory;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class Factory extends BuilderFactory
{
    /**
     * @var array|AbstractBuilder[]
     */
    protected $types = [
        'object' => ObjectBuilder::class,
    ];
}
