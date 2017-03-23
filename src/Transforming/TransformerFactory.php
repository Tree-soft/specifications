<?php

namespace Mildberry\Specifications\Transforming;

use Illuminate\Container\Container;
use Illuminate\Pipeline\Pipeline;
use Mildberry\Specifications\Exceptions\ProhibitedTransformationException;
use Mildberry\Specifications\Exceptions\ResolverNotFoundException;
use Mildberry\Specifications\Transforming\Resolvers\AbstractResolver;
use Mildberry\Specifications\Transforming\Transformers\AbstractTransformer;
use Mildberry\Specifications\Transforming\Transformers\SimpleType\Casters;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class TransformerFactory
{
    use ContainerAwareTrait;

    /**
     * @var AbstractResolver[]
     */
    private $resolvers = [];

    /**
     * @var callable[]
     */
    private $callbacks = [];

    /**
     * TransformerFactory constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->setContainer($container);

        foreach ($this->getInternalResolvers() as $name => $config) {
            /**
             * @var AbstractResolver $resolver
             */
            $resolver = $this->container->make($config['class']);

            $resolver
                ->setConfig($config);

            $this->registerResolver($name, $resolver);
        }
    }

    /**
     * @param string $from
     * @param string $to
     *
     * @return AbstractTransformer
     *
     * @throw
     */
    public function create($from, $to): AbstractTransformer
    {
        $pipeline = new Pipeline();

        return $pipeline
            ->send([$from, $to])
            ->through($this->getResolvers())
            ->then(function () use ($from, $to) {
                /**
                 * @var ProhibitedTransformationException $exception
                 */
                $exception = $this->container->make(ProhibitedTransformationException::class);

                $exception
                    ->setFrom($from)
                    ->setTo($to);

                throw $exception;
            });
    }

    /**
     * @return array
     */
    protected function getResolvers(): array
    {
        return array_map(function (string $name) {
            return $this->resolvers[$name];
        }, $this->callbacks);
    }

    /**
     * @param string $name
     * @param AbstractResolver $resolver
     * @param string $after
     *
     * @return $this
     */
    public function registerResolver(string $name, AbstractResolver $resolver, string $after = null)
    {
        $this->resolvers[$name] = $resolver;

        $id = isset($after) ? (array_search($after, $this->callbacks)) : (0);

        if ($id === false) {
            $this->callbacks[] = $name;
        } elseif ($id === 0) {
            array_unshift($this->callbacks, $name);
        } else {
            array_splice($this->callbacks, $id + 1, 0, [$name]);
        }

        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function removeResolver(string $name)
    {
        unset($this->resolvers[$name]);

        $this->callbacks[] = array_values(
            array_filter($this->callbacks, function (string $resolver) use ($name) {
                return $name != $resolver;
            })
        );

        return $this;
    }

    /**
     * @return callable[]
     */
    public function getCallbacks(): array
    {
        return $this->callbacks;
    }

    /**
     * @param string $name
     *
     * @throws ResolverNotFoundException
     *
     * @return AbstractResolver
     */
    public function getResolver(string $name)
    {
        if (!array_key_exists($name, $this->resolvers)) {
            throw new ResolverNotFoundException("Resolver '{$name}' not found");
        }

        return  $this->resolvers[$name];
    }

    /**
     * @return array
     */
    protected function getInternalResolvers(): array
    {
        return [
            'complex' => [
                'class' => Resolvers\ComplexSchemaResolver::class,
            ],
            'simple' => [
                'class' => Resolvers\SimpleTypeResolver::class,
                'casters' => [
                    'boolean' => Casters\BooleanCaster::class,
                    'number' => Casters\FloatCaster::class,
                    'string' => Casters\StringCaster::class,
                    'int' => Casters\IntegerCaster::class,
                ],
            ],
            'json' => [
                'class' => Resolvers\JsonSchemaResolver::class,
                'schema' => 'transform://transformations',
            ],
            'equal' => [
                'class' => Resolvers\CopyResolver::class,
            ],
        ];
    }
}
