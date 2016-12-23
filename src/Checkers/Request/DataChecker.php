<?php

namespace Mildberry\Specifications\Checkers\Request;

use Mildberry\Specifications\Exceptions\DataValidationException;
use Mildberry\Specifications\Exceptions\EntityValidationException;
use Mildberry\Specifications\Checkers\EntityChecker;
use Mildberry\Specifications\Support\DynamicSchemaInjectorTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class DataChecker extends EntityChecker
{
    use DynamicSchemaInjectorTrait;

    public function createException(string $message): EntityValidationException
    {
        return new DataValidationException($message);
    }
}
