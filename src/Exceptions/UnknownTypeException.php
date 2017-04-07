<?php

namespace TreeSoft\Specifications\Exceptions;

use Exception;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class UnknownTypeException extends Exception
{
    /**
     * @var object
     */
    private $schema;

    /**
     * @return object
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * @param object $schema
     *
     * @return $this
     */
    public function setSchema($schema)
    {
        $this->schema = $schema;

        return $this;
    }
}
