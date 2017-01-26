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
    public function save($entity);

    /**
     * @param mixed $queryObject
     */
    public function updateBy($queryObject);

    /**
     * @param mixed $entity
     */
    public function delete($entity);

    /**
     * @param mixed $queryObject
     */
    public function deleteBy($queryObject);
}
