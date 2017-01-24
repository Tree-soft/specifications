<?php

namespace Mildberry\Specifications\Transforming\Populator\Fillers;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class SetterFiller implements FillerInterface
{
    /**
     * @param object $entity
     * @param string $field
     * @param mixed $value
     */
    public function fill($entity, string $field, $value)
    {
        $setter = $this->getMethod($field);
        $entity->{$setter}($value);
    }

    /**
     * @param string $field
     *
     * @return string
     */
    public function getMethod(string $field): string
    {
        $property = ucfirst($field);

        return "set{$property}";
    }
}
