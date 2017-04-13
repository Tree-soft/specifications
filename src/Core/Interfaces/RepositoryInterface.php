<?php

namespace TreeSoft\Specifications\Core\Interfaces;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
interface RepositoryInterface
{
    /**
     * @param mixed $expression
     *
     * @return array
     */
    public function findBy($expression);

    /**
     * @param mixed $expression
     *
     * @return mixed
     */
    public function findOneBy($expression);

    /**
     * @param mixed $entity
     * @param mixed|null $expression
     *
     * @return mixed
     */
    public function insert($entity, $expression = null);

    /**
     * @param mixed $entity
     * @param mixed|null $expression
     *
     * @return mixed
     */
    public function update($entity, $expression = null);

    /**
     * @param mixed $expression
     */
    public function updateBy($expression);

    /**
     * @param mixed $entity
     */
    public function delete($entity);

    /**
     * @param mixed $expression
     */
    public function deleteBy($expression);

    /**
     * @param $expression
     *
     * @return bool
     */
    public function exists($expression): bool;
}
