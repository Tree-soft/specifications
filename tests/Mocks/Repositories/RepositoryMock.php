<?php

namespace TreeSoft\Tests\Specifications\Mocks\Repositories;

use TreeSoft\Specifications\Core\Interfaces\RepositoryInterface;
use TreeSoft\Specifications\Core\Specifications\SpecificationInterface;
use TreeSoft\Specifications\Support\Testing\CallsTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class RepositoryMock implements RepositoryInterface
{
    use CallsTrait;

    /**
     * @param mixed $specification
     *
     * @return array
     */
    public function findBy(SpecificationInterface $specification)
    {
        $this->_handle(__FUNCTION__, $specification);

        return [];
    }

    /**
     * @param mixed $specification
     *
     * @return mixed
     */
    public function findOneBy(SpecificationInterface $specification)
    {
        $this->_handle(__FUNCTION__, $specification);

        return null;
    }

    /**
     * @param mixed $entity
     * @param mixed|null $specification
     *
     * @return mixed
     */
    public function insert($entity, SpecificationInterface $specification = null)
    {
        $this->_handle(__FUNCTION__, $entity);

        return $entity;
    }

    /**
     * @param mixed $entity
     * @param mixed|null $specification
     *
     * @return mixed
     */
    public function update($entity, SpecificationInterface $specification = null)
    {
        $this->_handle(__FUNCTION__, $entity);

        return $entity;
    }

    /**
     * @param mixed $specification
     */
    public function updateBy(SpecificationInterface $specification)
    {
        $this->_handle(__FUNCTION__, $specification);
    }

    /**
     * @param mixed $entity
     * @param SpecificationInterface|null $specification
     */
    public function delete($entity, SpecificationInterface $specification = null)
    {
        $this->_handle(__FUNCTION__, $entity);
    }

    /**
     * @param mixed $specification
     */
    public function deleteBy(SpecificationInterface $specification)
    {
        $this->_handle(__FUNCTION__, $specification);
    }

    /**
     * @param SpecificationInterface $specification
     *
     * @return bool
     */
    public function exists(SpecificationInterface $specification): bool
    {
        $this->_handle(__FUNCTION__, $specification);

        return false;
    }
}
