<?php

namespace Mildberry\Specifications\Support\Transformers;

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
        $namespace = $this->config->get('specifications.namespace', '');

        $transformer
            ->setNamespace($namespace)
            ->setClass($class);

        return $transformer;
    }
}
