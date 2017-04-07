<?php

namespace TreeSoft\Specifications\Transforming;

use Illuminate\Container\Container;
use Illuminate\Pipeline\Pipeline;
use TreeSoft\Specifications\Exceptions\ProhibitedTransformationException;
use TreeSoft\Specifications\Exceptions\ResolverNotFoundException;
use TreeSoft\Specifications\Support\OrderedList;
use TreeSoft\Specifications\Transforming\Resolvers\AbstractResolver;
use TreeSoft\Specifications\Transforming\Resolvers\ArrayResolver;
use TreeSoft\Specifications\Transforming\Resolvers\NullResolver;
use TreeSoft\Specifications\Transforming\Transformers\AbstractTransformer;
use TreeSoft\Specifications\Transforming\Transformers\SimpleType\Casters;
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
     * @var OrderedList;
     */
    private $callbacks;

    /**
     * TransformerFactory constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->callbacks = new OrderedList();
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
        }, $this->callbacks->items());
    }

    /**
     * @param string $name
     * @param AbstractResolver $resolver
     * @param string $before
     *
     * @return $this
     */
    public function registerResolver(string $name, AbstractResolver $resolver, string $before = null)
    {
        $this->resolvers[$name] = $resolver;

        $this->callbacks->add($name, $before);

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

        $this->callbacks->remove($name);

        return $this;
    }

    /**
     * @return callable[]
     */
    public function getCallbacks(): array
    {
        return $this->callbacks->items();
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
            'equal' => [
                'class' => Resolvers\CopyResolver::class,
            ],
            'json' => [
                'class' => Resolvers\JsonSchemaResolver::class,
                'schema' => 'transform://transformations',
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
            'null' => [
                'class' => NullResolver::class,
            ],
            'complex' => [
                'class' => Resolvers\ComplexSchemaResolver::class,
            ],
            'array' => [
                'class' => ArrayResolver::class,
            ],
        ];
    }
}
