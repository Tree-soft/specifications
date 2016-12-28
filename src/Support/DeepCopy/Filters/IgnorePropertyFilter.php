<?php

namespace Mildberry\Specifications\Support\DeepCopy\Filters;

use DeepCopy\Filter\Filter;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class IgnorePropertyFilter implements Filter
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
        if (property_exists($this->value, $property)) {
            $object->{$property} = $this->value->{$property};
        } else {
            unset($object->{$property});
        }
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
