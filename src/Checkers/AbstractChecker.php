<?php

namespace TreeSoft\Specifications\Checkers;

use TreeSoft\Specifications\Support\FactoryInjectorTrait;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class AbstractChecker implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    use FactoryInjectorTrait;

    /**
     * @param mixed $data
     */
    abstract public function check($data);
}
