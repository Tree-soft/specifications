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

    /**
     * @return Dereferencer
     */
    public function dereferencer(): Dereferencer
    {
        $dereferencer = parent::dereferencer();

        $dereferencer->registerLoader(
            $this->container->make(Loader::class), 'schema'
        );

        $dereferencer->registerLoader(
            $this->container->make(TransformerLoader::class), 'transform'
        );

        return $dereferencer;
    }
}
