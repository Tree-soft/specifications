<?php

namespace Mildberry\Specifications\Specifications\Request;

use Mildberry\Specifications\Exceptions\DataValidationException;
use Mildberry\Specifications\Exceptions\EntityValidationException;
use Mildberry\Specifications\Specifications\EntitySpecification;
use Mildberry\Specifications\Support\DynamicSchemaInjectorTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class DataSpecification extends EntitySpecification
{
    use DynamicSchemaInjectorTrait;

    public function createException(string $message): EntityValidationException
    {
        return new DataValidationException($message);
    }
}
