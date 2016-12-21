<?php

namespace Mildberry\Specifications\Specifications;

use Mildberry\Specifications\Exceptions\EntityValidateException;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class EntitySpecification extends AbstractSpecification
{
    protected $schema;

    public function check($data)
    {
        $validator = $this->factory->validator($data, $this->schema);

        if ($validator->fails()) {
            $exception = new EntityValidateException('Cannot validate object.');
            $exception
                ->setData($data)
                ->setErrors($validator->errors());

            throw $exception;
        }
    }
}
