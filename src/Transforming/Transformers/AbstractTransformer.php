<?php

namespace Mildberry\Specifications\Transforming\Transformers;

use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class AbstractTransformer implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @param mixed $from
     * @param mixed|null $to
     *
     * @return mixed
     */
    abstract public function transform($from, $to = null);
}
