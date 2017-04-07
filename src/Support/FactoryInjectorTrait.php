<?php

namespace TreeSoft\Specifications\Support;

use TreeSoft\Specifications\Schema\Factory;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
trait FactoryInjectorTrait
{
    /**
     * @var Factory
     */
    protected $factory;

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
