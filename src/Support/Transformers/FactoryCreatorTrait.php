<?php

namespace TreeSoft\Specifications\Support\Transformers;

use Illuminate\Contracts\Config\Repository as Config;

/**
 * @author Sergei Melnikov <me@rnr.name>
 *
 * @property Config $config
 */
trait FactoryCreatorTrait
{
    /**
     * @param string $class
     * @param EntityTransformer $transformer
     *
     * @return EntityTransformer
     */
    public function fillTransformer(string $class, EntityTransformer $transformer)
    {
        $transformer
            ->setClass($class);

        return $transformer;
    }
}
