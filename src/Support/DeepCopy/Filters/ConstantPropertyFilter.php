<?php

namespace Mildberry\Specifications\Support\DeepCopy\Filters;

use DeepCopy\Filter\Filter;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ConstantPropertyFilter implements Filter
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @param object $object
     * @param string $property
     * @param callable $objectCopier
     */
    public function apply($object, $property, $objectCopier)
    {
        $object->{$property} = $objectCopier($this->value);
    }

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
}
