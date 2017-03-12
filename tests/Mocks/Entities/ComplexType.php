<?php

namespace Mildberry\Tests\Specifications\Mocks\Entities;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ComplexType
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var ObjectMock|null
     */
    private $object;

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

    /**
     * @return ObjectMock|null
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param ObjectMock|null $object
     *
     * @return $this
     */
    public function setObject($object)
    {
        $this->object = $object;

        return $this;
    }
}
