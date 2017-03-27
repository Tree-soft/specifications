<?php

namespace Mildberry\Specifications\Transforming\Fillers;

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
    public function fill(&$entity, string $field, $value)
    {
        if (is_null($value)) {
            return;
        }

        $setter = $this->setter($field);
        $entity->{$setter}($value);
    }

    /**
     * @param string $field
     *
     * @return string
     */
    public function setter(string $field): string
    {
        $property = ucfirst(camel_case($field));

        return "set{$property}";
    }

    /**
     * @param mixed $entity
     * @param string $field
     *
     * @return mixed
     */
    public function extract($entity, string $field)
    {
        $getter = $this->getter($field);

        return $entity->{$getter}();
    }

    /**
     * @param string $field
     *
     * @return string
     */
    public function getter(string $field): string
    {
        $property = ucfirst($field);

        return "get{$property}";
    }
}
