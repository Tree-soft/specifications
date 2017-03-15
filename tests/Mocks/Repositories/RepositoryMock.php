<?php

namespace Mildberry\Tests\Specifications\Mocks\Repositories;

use Mildberry\Specifications\Core\Interfaces\RepositoryInterface;
use Mildberry\Specifications\Support\Testing\CallsTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class RepositoryMock implements RepositoryInterface
{
    use CallsTrait;

    /**
     * @param mixed $queryOptions
     *
     * @return array
     */
    public function findBy($queryOptions)
    {
        $this->_handle(__FUNCTION__, $queryOptions);

        return [];
    }

    /**
     * @param mixed $queryOptions
     *
     * @return mixed
     */
    public function findOneBy($queryOptions)
    {
        $this->_handle(__FUNCTION__, $queryOptions);

        return null;
    }

    /**
     * @param mixed $entity
     * @param mixed|null $queryOptions
     *
     * @return mixed
     */
    public function insert($entity, $queryOptions = null)
    {
        $this->_handle(__FUNCTION__, $entity);

        return $entity;
    }

    /**
     * @param mixed $entity
     * @param mixed|null $queryOptions
     *
     * @return mixed
     */
    public function update($entity, $queryOptions = null)
    {
        $this->_handle(__FUNCTION__, $entity);

        return $entity;
    }

    /**
     * @param mixed $queryOptions
     */
    public function updateBy($queryOptions)
    {
        $this->_handle(__FUNCTION__, $queryOptions);
    }

    /**
     * @param mixed $entity
     */
    public function delete($entity)
    {
        $this->_handle(__FUNCTION__, $entity);
    }

    /**
     * @param mixed $queryOptions
     */
    public function deleteBy($queryOptions)
    {
        $this->_handle(__FUNCTION__, $queryOptions);
    }
}
