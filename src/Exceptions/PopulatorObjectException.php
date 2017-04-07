<?php

namespace TreeSoft\Specifications\Exceptions;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class PopulatorObjectException extends PopulatorException
{
    /**
     * @var string
     */
    private $field;

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
     * @return string
     */
    public function __toString()
    {
        return "Cannot populate field '{$this->field}' in object";
    }
}
