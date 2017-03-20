<?php

namespace Mildberry\Specifications\Support\Resolvers\Transform;

use Mildberry\Specifications\Http\Transformers\EntityTransformerFactory;

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
