<?php

namespace Mildberry\Specifications\Transforming\Converter;

use Closure;
use Illuminate\Pipeline\Pipeline;
use Mildberry\Specifications\Exceptions\PopulatorException;
use Mildberry\Specifications\Exceptions\PopulatorObjectException;
use Mildberry\Specifications\Schema\LaravelFactory;
use Mildberry\Specifications\Support\Resolvers\SpecificationsNamespace\NamespaceAwareInterface;
use Mildberry\Specifications\Support\Resolvers\SpecificationsNamespace\NamespaceAwareTrait;
use Mildberry\Specifications\Transforming\Converter\Resolvers\AbstractResolver;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class Converter implements ContainerAwareInterface, NamespaceAwareInterface
{
    use ContainerAwareTrait;
    use NamespaceAwareTrait;

    /**
     * @var LaravelFactory $factory
     */
    protected $factory;

    /**
     * @var array
     */
    protected $resolvers = [];

    /**
     * Populator constructor.
     *
     * @param LaravelFactory $factory
     */
    public function __construct(LaravelFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param mixed $data
     * @param string|object $schema
     *
     * @throws PopulatorObjectException
     *
     * @return mixed
     */
    public function convert($data, $schema)
    {
        $schema = $this->factory->schema($schema);

        $resolvers = $this->createResolvers($schema);

        $pipeline = new Pipeline();

        return $pipeline
            ->send($data)
            ->through($resolvers)
            ->then(function () use ($data) {
                $e = new PopulatorException('Cannot populate data');

                $e->setData($data);

                throw $e;
            });
    }

    /**
     * @param object $schema
     *
     * @return array
     */
    protected function createResolvers($schema): array
    {
        $resolvers = $this->resolvers;

        return array_map(function ($config) use ($schema) {
            return $this->wrapResolver($config, $schema);
        }, $resolvers);
    }

    /**
     * @param array $config
     * @param object $schema
     *
     * @return Closure
     */
    protected function wrapResolver(array $config, $schema): Closure
    {
        /**
         * @var AbstractResolver $resolver
         */
        $resolver = $this->container->make($config['class']);

        $resolver
            ->setConfig($config)
            ->setSchema($schema)
            ->setConverter($this);

        return function ($data, $next) use ($resolver) {
            return $resolver->resolve($data, $next);
        };
    }

    /**
     * @return array
     */
    public function getResolvers(): array
    {
        return $this->resolvers;
    }

    /**
     * @param array $resolvers
     *
     * @return $this
     */
    public function setResolvers(array $resolvers)
    {
        $this->resolvers = $resolvers;

        return $this;
    }
}
