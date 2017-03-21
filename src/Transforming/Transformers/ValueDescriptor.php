<?php

namespace Mildberry\Specifications\Transforming\Transformers;

/**
 * Class ValueDescriptor.
 */
class ValueDescriptor
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @var mixed|object
     */
    private $schema;

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return mixed|object
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * @param mixed|object $schema
     *
     * @return $this
     */
    public function setSchema($schema)
    {
        $this->schema = $schema;

        return $this;
    }
}
