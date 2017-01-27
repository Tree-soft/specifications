<?php

namespace Mildberry\Specifications\Core\Interfaces;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
interface RepositoryInterface
{
    /**
     * @param mixed $queryOptions
     *
     * @return array
     */
    public function findBy($queryOptions);

    /**
     * @param mixed $queryOptions
     *
     * @return mixed
     */
    public function findOneBy($queryOptions);

    /**
     * @param mixed $entity
     *
     * @return mixed
     */
    public function insert($entity);

    /**
     * @param mixed $entity
     *
     * @return mixed
     */
    public function update($entity);

    /**
     * @param mixed $queryOptions
     */
    public function updateBy($queryOptions);

    /**
     * @param mixed $entity
     */
    public function delete($entity);

    /**
     * @param mixed $queryOptions
     */
    public function deleteBy($queryOptions);
}
