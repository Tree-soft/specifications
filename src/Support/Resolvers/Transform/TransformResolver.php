<?php

namespace TreeSoft\Specifications\Support\Resolvers\Transform;

use TreeSoft\Specifications\Http\Transformers\EntityTransformerFactory;
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
