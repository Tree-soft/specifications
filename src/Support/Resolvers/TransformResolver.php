<?php

namespace Mildberry\Specifications\Support\Resolvers;

use Mildberry\Specifications\Http\Transformers\EntityTransformerFactory;
use Rnr\Resolvers\Resolvers\AbstractResolver;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class TransformResolver extends AbstractResolver
{
    /**
     * @param TransformAwareInterface $object
     */
    public function bind($object)
    {
        $object->setTransformer($this->container->make(EntityTransformerFactory::class));
    }
}
