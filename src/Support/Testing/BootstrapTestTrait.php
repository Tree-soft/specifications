<?php

namespace TreeSoft\Specifications\Support\Testing;

use ReflectionClass;
use ReflectionMethod;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
trait BootstrapTestTrait
{
    protected function boot()
    {
        $reflection = new ReflectionClass($this);

        /**
         * @var ReflectionMethod[] $methods
         */
        $methods = array_filter($reflection->getMethods(), function (ReflectionMethod $method) {
            return starts_with($method->getName(), 'bootstrap') && ($method->getNumberOfParameters() == 0);
        });

        foreach ($methods as $method) {
            $this->{$method->getName()}();
        }
    }
}
