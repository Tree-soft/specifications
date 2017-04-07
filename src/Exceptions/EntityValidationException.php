<?php

namespace TreeSoft\Specifications\Exceptions;

use Exception;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class EntityValidationException extends Exception
{
    /**
     * @var array
     */
    private $errors = [];

    /**
     * @var mixed
     */
    private $data;

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     *
     * @return $this
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $message = $this->getMessage();
        $data = json_encode($this->data);
        $errors = json_encode($this->errors);

        return "'{$message}' Data '{$data}', errors '{$errors}";
    }
}
