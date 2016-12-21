<?php

namespace Mildberry\Specifications\Schema;

use League\JsonGuard\Dereferencer;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class LaravelFactory extends Factory implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function dereferencer(): Dereferencer
    {
        $dereferencer = parent::dereferencer();
        $loader = $this->container->make(Loader::class);
        $dereferencer->registerLoader($loader, 'schema');

        return $dereferencer;
    }
}
