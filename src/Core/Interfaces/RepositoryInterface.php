<?php

namespace TreeSoft\Specifications\Core\Interfaces;

use TreeSoft\Specifications\Core\Specifications\SpecificationInterface;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
interface RepositoryInterface
{
    /**
     * @param SpecificationInterface $specification
     *
     * @return array
     */
    public function findBy(SpecificationInterface $specification);

    /**
     * @param SpecificationInterface $specification
     *
     * @return mixed
     */
    public function findOneBy(SpecificationInterface $specification);

    /**
     * @param mixed $entity
     * @param SpecificationInterface|null $specification
     *
     * @return mixed
     */
    public function insert($entity, SpecificationInterface $specification = null);

    /**
     * @param mixed $entity
     * @param SpecificationInterface|null $specification
     *
     * @return mixed
     */
    public function update($entity, SpecificationInterface $specification = null);

    /**
     * @param SpecificationInterface $specification
     *
     * @return
     */
    public function updateBy(SpecificationInterface $specification);

    /**
     * @param mixed $entity
     * @param SpecificationInterface|null $specification
     *
     * @return
     */
    public function delete($entity, SpecificationInterface $specification = null);

    /**
     * @param SpecificationInterface $specification
     *
     * @return
     */
    public function deleteBy(SpecificationInterface $specification);

    /**
     * @param SpecificationInterface $specification
     *
     * @return bool
     */
    public function exists(SpecificationInterface $specification): bool;
}
