<?php

namespace Mildberry\Specifications\Checkers;

use Mildberry\Specifications\Schema\Factory;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class AbstractChecker implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var Factory
     */
    protected $factory;

    abstract public function check($data);

    /**
     * @return Factory
     */
    public function getFactory(): Factory
    {
        return $this->factory;
    }

    /**
     * @param Factory $factory
     *
     * @return $this
     */
    public function setFactory(Factory $factory)
    {
        $this->factory = $factory;

        return $this;
    }
}
