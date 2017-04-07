<?php

namespace TreeSoft\Specifications\Checkers;

use TreeSoft\Specifications\Exceptions\EntityValidationException;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class EntityChecker extends AbstractChecker
{
    /**
     * @var string|object
     */
    protected $schema;

    /**
     * @param mixed $data
     *
     * @throws EntityValidationException
     */
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
