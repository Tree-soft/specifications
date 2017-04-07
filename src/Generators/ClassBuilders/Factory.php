<?php

namespace TreeSoft\Specifications\Generators\ClassBuilders;

use TreeSoft\Specifications\Generators\AbstractBuilder;
use TreeSoft\Specifications\Generators\BuilderFactory;

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
