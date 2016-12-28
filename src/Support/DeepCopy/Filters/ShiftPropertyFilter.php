<?php

namespace Mildberry\Specifications\Support\DeepCopy\Filters;

use DeepCopy\Filter\Filter;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ShiftPropertyFilter implements Filter
{
    /**
     * @var mixed
     */
    private $to;

    /**
     * @param object $object
     * @param string $property
     * @param callable $objectCopier
     */
    public function apply($object, $property, $objectCopier)
    {
        $object->{$this->to} = $object->{$property};
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param mixed $to
     *
     * @return $this
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }
}
