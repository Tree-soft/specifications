<?php

namespace TreeSoft\Tests\Specifications\Mocks\Entities;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ObjectMock
{
    /**
     * @var int
     */
    private $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }
}
