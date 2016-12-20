<?php

namespace Mildberry\Specifications\Specifications;

use Mildberry\Specifications\Exceptions\EntityValidateException;
use Mildberry\Specifications\Schema\Factory;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class EntitySpecification implements SpecificationInterface
{
    protected $schema;

    public function check($data)
    {
        $factory = new Factory();

        $validator = $factory->validator($data, $this->schema);

        if ($validator->fails()) {
            $exception = new EntityValidateException('Cannot validate object.');

            throw $exception;
        }
    }
}
