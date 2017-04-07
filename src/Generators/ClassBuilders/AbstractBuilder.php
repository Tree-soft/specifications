<?php

namespace TreeSoft\Specifications\Generators\ClassBuilders;

use TreeSoft\Specifications\Generators\AbstractBuilder as ParentBuilder;
use TreeSoft\Specifications\Generators\ClassGenerator;

/**
 * @author Sergei Melnikov <me@rnr.name>
 *
 * @method AbstractBuilder setGenerator(ClassGenerator $generator)
 * @method ClassGenerator getGenerator()
 */
abstract class AbstractBuilder extends ParentBuilder
{
    /**
     * @var ClassGenerator
     */
    protected $generator;
}
