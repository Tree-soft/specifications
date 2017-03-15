<?php

namespace Mildberry\Tests\Specifications\Fixtures\Entities;

/**
 * @author Json-schema class generator
 */
class Nullable
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
