<?php

namespace Mildberry\Specifications\Exceptions;

use Exception;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class PopulatorException extends Exception
{
    /**
     * @var string
     */
    private $field;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @return mixed
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param mixed $field
     *
     * @return $this
     */
    public function setField($field)
    {
        $this->field = $field;

        $this->message = (string) $this;

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

        $this->message = (string) $this;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "Cannot populate field '{$this->field}' in object";
    }
}
