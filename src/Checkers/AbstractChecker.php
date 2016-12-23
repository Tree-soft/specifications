<?php

namespace Mildberry\Specifications\Checkers;

use Mildberry\Specifications\Support\FactoryInjectorTrait;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class AbstractChecker implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    use FactoryInjectorTrait;

    abstract public function check($data);
}
