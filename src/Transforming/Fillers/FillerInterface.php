<?php

namespace Mildberry\Specifications\Transforming\Fillers;

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
    public function fill(&$entity, string $field, $value);

    /**
     * @param mixed $entity
     * @param string $field
     *
     * @return mixed
     */
    public function extract($entity, string $field);
}
