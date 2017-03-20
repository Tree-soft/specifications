<?php

namespace Mildberry\Specifications\Support\Resolvers\Transform;

use Mildberry\Specifications\Http\Transformers\EntityTransformerFactory;

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
