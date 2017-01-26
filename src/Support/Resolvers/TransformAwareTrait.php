<?php

namespace Mildberry\Specifications\Support\Resolvers;

use Mildberry\Specifications\Http\Transformers\EntityTransformerFactory;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class TransformAwareTrait
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
