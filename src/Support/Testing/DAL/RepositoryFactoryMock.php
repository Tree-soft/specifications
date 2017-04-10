<?php

namespace TreeSoft\Specifications\Support\Testing\DAL;

use TreeSoft\Specifications\Core\Interfaces\RepositoryInterface;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;
use TreeSoft\Specifications\DAL\Factories\DefaultFactory;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class RepositoryFactoryMock extends DefaultFactory implements ContainerAwareInterface
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
        $this->check($class);

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
