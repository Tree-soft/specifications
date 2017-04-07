<?php

namespace TreeSoft\Specifications\Support\Resolvers\Transform;

use TreeSoft\Specifications\Http\Transformers\EntityTransformerFactory;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
interface TransformAwareInterface
{
    /**
     * @param EntityTransformerFactory $factory
     */
    public function setTransformer(EntityTransformerFactory $factory);
}
