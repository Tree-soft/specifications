<?php

namespace TreeSoft\Specifications\Support\Testing\DAL;

use TreeSoft\Specifications\Core\Interfaces\RepositoryFactoryInterface;
use TreeSoft\Specifications\Core\Interfaces\RepositoryInterface;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class RepositoryFactoryMock implements RepositoryFactoryInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var mixed
     */
    private $mock;

    /**
     * @param string $class
     *
     * @return RepositoryInterface
     */
    public function create(string $class): RepositoryInterface
    {
        return value($this->mock);
    }

    /**
     * @return mixed
     */
    public function getMock()
    {
        return $this->mock;
    }

    /**
     * @param mixed $mock
     *
     * @return $this
     */
    public function setMock($mock)
    {
        $this->mock = $mock;

        return $this;
    }
}
