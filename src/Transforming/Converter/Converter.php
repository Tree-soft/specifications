<?php

namespace Mildberry\Specifications\Transforming\Converter;

use Illuminate\Pipeline\Pipeline;
use Mildberry\Specifications\Exceptions\PopulatorException;
use Mildberry\Specifications\Exceptions\PopulatorObjectException;
use Mildberry\Specifications\Schema\LaravelFactory;
use Mildberry\Specifications\Support\Resolvers\SpecificationsNamespace\NamespaceAwareInterface;
use Mildberry\Specifications\Support\Resolvers\SpecificationsNamespace\NamespaceAwareTrait;
use Mildberry\Specifications\Transforming\Converter\Resolvers\AbstractResolver;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;
use Mildberry\Specifications\Transforming\Converter\Resolvers as ConverterResolvers;

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
    private $resolvers = [];

    /**
     * @var string[]
     */
    protected $resolversOrder = [];

    /**
     * Populator constructor.
     *
     * @param LaravelFactory $factory
     */
    public function __construct(LaravelFactory $factory)
    {
        $this->factory = $factory;

        $lastResolver = null;

        foreach ($this->getInternalResolvers() as $name => $resolver) {
            $this->registerResolver($name, $resolver, $lastResolver);

            $lastResolver = $name;
        }
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
            ->via('resolve')
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
        return array_map(function ($config) use ($schema) {
            /**
             * @var AbstractResolver $resolver
             */
            $resolver = $this->container->make($config['class']);

            $resolver
                ->setConfig($config)
                ->setSchema($schema)
                ->setConverter($this);

            return $resolver;
        }, $this->resolvers);
    }

    /**
     * @return array
     */
    public function getResolvers(): array
    {
        return $this->resolvers;
    }

    /**
     * @return array
     */
    public function getInternalResolvers(): array
    {
        return [
            'nullable' => [
                'class' => ConverterResolvers\NullableResolver::class,
            ],
            'simple' => [
                'class' => ConverterResolvers\SimpleResolver::class,
            ],
            'complex' => [
                'class' => ConverterResolvers\ComplexResolver::class,
            ],
        ];
    }

    /**
     * @param string $name
     * @param array $definition
     * @param string|null $after
     *
     * @return $this
     */
    public function registerResolver(string $name, array $definition, string $after = null)
    {
        $this->resolvers[$name] = $definition;

        $id = isset($after) ? (array_search($after, $this->resolversOrder)) : (0);

        if ($id === false) {
            $this->resolversOrder[] = $name;
        } elseif ($id === 0) {
            array_unshift($this->resolversOrder, $name);
        } else {
            array_splice($this->resolversOrder, $id + 1, 0, [$name]);
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

        $this->resolversOrder[] = array_values(
            array_filter($this->resolversOrder, function (string $resolver) use ($name) {
                return $name != $resolver;
            })
        );

        return $this;
    }
}
