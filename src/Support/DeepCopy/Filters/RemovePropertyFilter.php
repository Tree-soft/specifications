<?php

namespace Mildberry\Specifications\Support\DeepCopy\Filters;

use DeepCopy\Filter\Filter;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class RemovePropertyFilter implements Filter
{
    /**
     * @param object $object
     * @param string $property
     * @param callable $objectCopier
     */
    public function apply($object, $property, $objectCopier)
    {
        unset($object->{$property});
    }
}
