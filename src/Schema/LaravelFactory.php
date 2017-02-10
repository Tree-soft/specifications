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

        $loaderManager = $dereferencer->getLoaderManager();

        $loaderManager->registerLoader(
            'schema', $this->container->make(Loader::class)
        );

        $loaderManager->registerLoader(
            'transform', $this->container->make(TransformerLoader::class)
        );

        return $dereferencer;
    }
}
