<?php

namespace Mildberry\Specifications\Specifications;

use Mildberry\Specifications\Exceptions\EntityValidationException;

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
            $exception = $this->createException('Cannot validate object.');

            $exception
                ->setData($data)
                ->setErrors($validator->errors());

            throw $exception;
        }
    }

    /**
     * @param string $message
     *
     * @return EntityValidationException
     */
    public function createException(string $message): EntityValidationException
    {
        return new EntityValidationException($message);
    }
}
