<?php

namespace Mildberry\Specifications\Transforming\Populator\Fillers;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
interface FillerInterface
{
    /**
     * @param object $entity
     * @param string $field
     * @param mixed $value
     */
    public function fill($entity, string $field, $value);
}
