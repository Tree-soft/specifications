<?php

namespace Mildberry\Specifications\Transforming\Populator;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class Populator
{
    /**
     * @param mixed $data
     * @param string $class
     *
     * @return mixed
     */
    public function populate($data, $class)
    {
        $entity = new $class();

        return $entity;
    }
}
