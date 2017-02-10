<?php

namespace Mildberry\Specifications\DAL\Transformers;

use Mildberry\Specifications\Support\Transformers\FactoryCreatorTrait;
use Rnr\Resolvers\Interfaces\ConfigAwareInterface;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ConfigAwareTrait;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class EntityTransformerFactory implements ConfigAwareInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;
    use ConfigAwareTrait;
    use FactoryCreatorTrait;

    /**
     * @param string $class
     *
     * @return EntityTransformer
     */
    public function create(string $class): EntityTransformer
    {
        /**
         * @var EntityTransformer $transformer
         */
        $transformer = $this->container->make(EntityTransformer::class);

        $this->fillTransformer($class, $transformer);

        return $transformer;
    }
}
