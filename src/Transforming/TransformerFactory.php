<?php

namespace Mildberry\Specifications\Transforming;

use Illuminate\Pipeline\Pipeline;
use Mildberry\Specifications\Exceptions\ProhibitedTransformationException;
use Mildberry\Specifications\Transforming\Resolvers\AbstractResolver;
use Mildberry\Specifications\Transforming\Transformers\AbstractTransformer;
use Rnr\Resolvers\Interfaces\ConfigAwareInterface;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ConfigAwareTrait;
use Closure;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class TransformerFactory implements ConfigAwareInterface, ContainerAwareInterface
{
    use ConfigAwareTrait;
    use ContainerAwareTrait;

    /**
     * @var array|callable[]
     */
    private $resolvers;

    /**
     * @param string $from
     * @param string $to
     *
     * @return AbstractTransformer
     *
     * @throw
     */
    public function create(string $from, string $to): AbstractTransformer
    {
        $pipeline = new Pipeline();

        return $pipeline
            ->send([$from, $to])
            ->through($this->getResolvers())
            ->then(function () use ($from, $to) {
                throw new ProhibitedTransformationException("Transformation '{$from}' to '{$to}' is prohibited");
            });
    }

    /**
     * @return array
     */
    protected function getResolvers(): array
    {
        if (empty($this->resolvers)) {
            $config = $this->config->get('specifications.transform.resolvers', []);

            $this->resolvers = array_map([$this, 'wrapResolver'], $config);
        }

        return $this->resolvers;
    }

    /**
     * @param array $config
     *
     * @return Closure
     */
    protected function wrapResolver(array $config): Closure
    {
        /**
         * @var AbstractResolver $resolver
         */
        $resolver = $this->container->make($config['class']);

        $resolver
            ->setConfig($config);

        return function ($passable, $next) use ($resolver) {
            list($from, $to) = $passable;

            return $resolver->resolve($from, $to, $next);
        };
    }
}
