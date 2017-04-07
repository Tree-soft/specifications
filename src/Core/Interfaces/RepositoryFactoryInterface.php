<?php

namespace TreeSoft\Specifications\Core\Interfaces;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
interface RepositoryFactoryInterface
{
    /**
     * @param string $class
     *
     * @return RepositoryInterface
     */
    public function create(string $class): RepositoryInterface;
}
