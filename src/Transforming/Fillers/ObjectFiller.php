<?php

namespace TreeSoft\Specifications\Transforming\Fillers;

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

    /**
     * @param mixed $object
     * @param string $field
     *
     * @return mixed
     */
    public function extract($object, string $field)
    {
        return $object->{$field};
    }
}
