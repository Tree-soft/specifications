<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Transformations;

use Mildberry\Specifications\Transforming\Transformers\ValueDescriptor;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class AbstractTransformation implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @param array $args
     * @param callable $next
     *
     * @return mixed
     */
    public function handle($args, $next)
    {
        list($from, $value) = $args;

        return $this->apply($from, $value, function ($from, $value) use ($next) {
            return $next([$from, $value]);
        });
    }

    /**
     * @param ValueDescriptor $from
     * @param ValueDescriptor $value
     * @param callable $next
     *
     * @return mixed
     */
    abstract public function apply(ValueDescriptor $from, ValueDescriptor $value, $next): ValueDescriptor;
}
