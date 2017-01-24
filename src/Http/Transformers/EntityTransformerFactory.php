<?php

namespace Mildberry\Specifications\Http\Transformers;

use Rnr\Resolvers\Interfaces\ConfigAwareInterface;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ConfigAwareTrait;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class EntityTransformerFactory implements ConfigAwareInterface, ContainerAwareInterface
{
    use ConfigAwareTrait;
    use ContainerAwareTrait;

    /**
     * @param string $class
     *
     * @return EntityTransformer
     */
    public function create(string $class): EntityTransformer
    {
        $namespace = $this->config->get('specifications.namespace', '');

        /**
         * @var EntityTransformer $transformer
         */
        $transformer = $this->container->make(EntityTransformer::class);

        $transformer
            ->setNamespace($namespace)
            ->setClass($class);

        return $transformer;
    }
}
