<?php

namespace Mildberry\Specifications\Transforming\Converter;
use Closure;
use Illuminate\Pipeline\Pipeline;
use Mildberry\Specifications\Exceptions\PopulatorException;
use Mildberry\Specifications\Schema\LaravelFactory;
use Mildberry\Specifications\Transforming\Converter\Resolvers\AbstractResolver;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;


/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class Converter implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var LaravelFactory $factory
     */
    protected $factory;
    /**
     * @var string
     */
    protected $namespace = '\\';

    /**
     * @var string
     */
    protected $filler;

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
     * @throws PopulatorException
     *
     * @return mixed
     */
    public function convert($data, $schema)
    {
        $schema = $this->factory->schema($schema);

        $resolvers = $this->createResolvers($schema, $data);
        $filler = $this->container->make($this->filler);

        $entity = $this->createEntity($schema, $data);

        $pipeline = new Pipeline();

        foreach (array_keys((array)$schema->properties) as $property) {
            try {
                $value = $pipeline
                    ->send($property)
                    ->through($resolvers)
                    ->then(function () use ($property) {
                        $e = new PopulatorException('Cannot populate object');

                        throw $e;
                    });

                $filler->fill($entity, $property, $value);
            } catch (PopulatorException $e) {
                $parts = array_filter([$property, $e->getField()], function ($part) {
                    return !is_null($part);
                });

                $e
                    ->setField(implode('.', $parts))
                    ->setData($data);

                throw $e;
            }
        }

        return $entity;
    }

    /**
     * @param mixed $data
     * @param object $schema
     *
     * @return mixed
     */
    abstract public function createEntity($schema, $data);

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     *
     * @return $this
     */
    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @param object $schema
     * @param object $data
     *
     * @return array
     */
    protected function createResolvers($schema, $data): array
    {
        $resolvers = $this->resolvers;

        return array_map(function ($config) use ($schema, $data) {
            return $this->wrapResolver($config, $schema, $data);
        }, $resolvers);
    }

    /**
     * @param array $config
     * @param object $schema
     * @param $data
     *
     * @return Closure
     */
    protected function wrapResolver(array $config, $schema, $data): Closure
    {
        /**
         * @var AbstractResolver $resolver
         */
        $resolver = $this->container->make($config['class']);

        $resolver
            ->setConfig($config)
            ->setSchema($schema)
            ->setData($data)
            ->setConverter($this);

        return function ($property, $next) use ($resolver) {
            return $resolver->resolve($property, $next);
        };
    }

    /**
     * @return string
     */
    public function getFiller(): string
    {
        return $this->filler;
    }

    /**
     * @param string $filler
     * @return $this
     */
    public function setFiller(string $filler)
    {
        $this->filler = $filler;

        return $this;
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
     * @return $this
     */
    public function setResolvers(array $resolvers)
    {
        $this->resolvers = $resolvers;

        return $this;
    }
}