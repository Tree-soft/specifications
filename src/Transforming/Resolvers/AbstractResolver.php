<?php

namespace TreeSoft\Specifications\Transforming\Resolvers;

use TreeSoft\Specifications\Transforming\Transformers\AbstractTransformer;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class AbstractResolver implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var array
     */
    private $config;

    /**
     * @param array $passable
     * @param callable $next
     *
     * @return AbstractTransformer
     */
    public function handle($passable, $next): AbstractTransformer
    {
        list($from, $to) = $passable;

        return $this->resolve(
            $from, $to, function ($from, $to) use ($next): AbstractTransformer {
                return $next([$from, $to]);
            }
        );
    }

    /**
     * @param string|object|mixed $from
     * @param string|object|mixed $to
     * @param callable $next
     *
     * @return AbstractTransformer
     */
    abstract public function resolve($from, $to, $next): AbstractTransformer;

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param array $config
     *
     * @return $this
     */
    public function setConfig(array $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @param string|object|mixed $from
     * @param string|object|mixed $to
     * @param callable $next
     *
     * @return AbstractTransformer
     */
    public function next($from, $to, $next): AbstractTransformer
    {
        return $next([$from, $to]);
    }
}
