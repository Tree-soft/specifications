<?php

namespace TreeSoft\Specifications\Support\Resolvers\Transform;

use TreeSoft\Specifications\Http\Transformers\EntityTransformerFactory;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
trait TransformAwareTrait
{
    /**
     * @var EntityTransformerFactory
     */
    protected $transformerFactory;

    /**
     * @param EntityTransformerFactory $factory
     */
    public function setTransformer(EntityTransformerFactory $factory)
    {
        $this->transformerFactory = $factory;
    }
}
