<?php

namespace TreeSoft\Specifications\Checkers\Request;

use TreeSoft\Specifications\Exceptions\EntityValidationException;
use TreeSoft\Specifications\Exceptions\RouteValidationException;
use TreeSoft\Specifications\Checkers\EntityChecker;
use TreeSoft\Specifications\Support\DynamicSchemaInjectorTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class RouteChecker extends EntityChecker
{
    use DynamicSchemaInjectorTrait;

    public function createException(string $message): EntityValidationException
    {
        return new RouteValidationException($message);
    }
}
