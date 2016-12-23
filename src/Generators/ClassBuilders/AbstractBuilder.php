<?php

namespace Mildberry\Specifications\Generators\ClassBuilders;

use Mildberry\Specifications\Generators\AbstractBuilder as ParentBuilder;
use Mildberry\Specifications\Generators\ClassGenerator;

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
