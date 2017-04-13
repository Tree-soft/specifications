<?php

namespace TreeSoft\Tests\Specifications\Mocks\Repositories;

use TreeSoft\Specifications\Core\Interfaces\RepositoryInterface;
use TreeSoft\Specifications\Support\Testing\CallsTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class RepositoryMock implements RepositoryInterface
{
    use CallsTrait;

    /**
     * @param mixed $expression
     *
     * @return array
     */
    public function findBy($expression)
    {
        $this->_handle(__FUNCTION__, $expression);

        return [];
    }

    /**
     * @param mixed $expression
     *
     * @return mixed
     */
    public function findOneBy($expression)
    {
        $this->_handle(__FUNCTION__, $expression);

        return null;
    }

    /**
     * @param mixed $entity
     * @param mixed|null $expression
     *
     * @return mixed
     */
    public function insert($entity, $expression = null)
    {
        $this->_handle(__FUNCTION__, $entity);

        return $entity;
    }

    /**
     * @param mixed $entity
     * @param mixed|null $expression
     *
     * @return mixed
     */
    public function update($entity, $expression = null)
    {
        $this->_handle(__FUNCTION__, $entity);

        return $entity;
    }

    /**
     * @param mixed $expression
     */
    public function updateBy($expression)
    {
        $this->_handle(__FUNCTION__, $expression);
    }

    /**
     * @param mixed $entity
     */
    public function delete($entity)
    {
        $this->_handle(__FUNCTION__, $entity);
    }

    /**
     * @param mixed $expression
     */
    public function deleteBy($expression)
    {
        $this->_handle(__FUNCTION__, $expression);
    }

    /**
     * @param $expression
     *
     * @return bool
     */
    public function exists($expression): bool
    {
        $this->_handle(__FUNCTION__, $expression);

        return false;
    }
}
