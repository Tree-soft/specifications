<?php

namespace TreeSoft\Specifications\Core\Services;

use TreeSoft\Specifications\Core\Interfaces\RepositoryFactoryInterface;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class AbstractDatabaseService extends AbstractService
{
    /**
     * @var RepositoryFactoryInterface
     */
    protected $repositoryFactory;

    public function afterResolving()
    {
        parent::afterResolving();

        $this->repositoryFactory = $this->container->make(RepositoryFactoryInterface::class);
    }
}
