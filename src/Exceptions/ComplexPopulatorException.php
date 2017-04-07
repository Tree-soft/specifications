<?php

namespace TreeSoft\Specifications\Exceptions;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ComplexPopulatorException extends PopulatorException
{
    /**
     * @var array|PopulatorObjectException[]
     */
    private $exceptions = [];

    /**
     * @param PopulatorException $e
     *
     * @return $this
     */
    public function addException(PopulatorException $e)
    {
        $this->exceptions[] = $e;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return implode("\n", array_map(function (PopulatorException $e) {
            return (string) $e;
        }, $this->exceptions));
    }
}
