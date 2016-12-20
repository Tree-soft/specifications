<?php

namespace Mildberry\Specifications\Schema;

use Illuminate\Contracts\Container\Container;
use League\JsonGuard\Dereferencer;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class LaravelFactory extends Factory
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function dereferencer(): Dereferencer
    {
        $dereferencer = parent::dereferencer();
        $loader = $this->container->make(Loader::class);
        $dereferencer->registerLoader($loader, 'schema');

        return $dereferencer;
    }
}
