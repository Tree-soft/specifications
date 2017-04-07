<?php

namespace TreeSoft\Specifications\Exceptions;

use Exception;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class PopulatorException extends Exception
{
    /**
     * @var mixed
     */
    protected $data;

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
        $data = var_export($this->data, true);

        return "Cannot populate '{$data}'";
    }
}
