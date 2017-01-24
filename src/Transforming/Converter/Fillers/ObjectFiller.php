<?php

namespace Mildberry\Specifications\Transforming\Converter\Fillers;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ObjectFiller implements FillerInterface
{
    /**
     * @param object $object
     * @param string $field
     * @param mixed $value
     */
    public function fill(&$object, string $field, $value)
    {
        if (is_null($value)) {
            return;
        }

        $object->{$field} = $value;
    }
}
