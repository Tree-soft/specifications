<?php

namespace Mildberry\Specifications\Core\Services;

use Mildberry\Specifications\Core\Interfaces\RepositoryFactoryInterface;

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
