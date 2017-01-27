<?php

namespace Mildberry\Specifications\DAL\Factories;

use Mildberry\Specifications\Core\Interfaces\RepositoryFactoryInterface;
use Mildberry\Specifications\Core\Interfaces\RepositoryInterface;
use Mildberry\Specifications\DAL\Exceptions\RepositoryException;
use Mildberry\Specifications\DAL\Exceptions\RepositoryNotFoundException;
use Rnr\Resolvers\Interfaces\ConfigAwareInterface;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ConfigAwareTrait;
use Rnr\Resolvers\Traits\ContainerAwareTrait;


/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class DefaultFactory implements RepositoryFactoryInterface, ConfigAwareInterface, ContainerAwareInterface
{
    use ConfigAwareTrait;
    use ContainerAwareTrait;

    /**
     * @var array|RepositoryInterface[]
     */
    private $repositories = [];

    /**
     * @param string $class
     *
     * @return RepositoryInterface
     */
    public function create(string $class): RepositoryInterface
    {
        return $this->repositories[$class] ?? $this->cacheRepository($class);
    }

    /**
     * @param string $class
     *
     * @return RepositoryInterface
     *
     * @throws RepositoryNotFoundException
     */
    protected function cacheRepository(string $class): RepositoryInterface {
        $repositories = $this->config->get('dal.repositories', []);

        if (!array_key_exists($class, $repositories)) {
            throw new RepositoryNotFoundException("Repository for class '{$class}' not found");
        }

        $this->repositories[$class] = $this->container->make($repositories[$class]);

        return $this->repositories[$class];
    }
}