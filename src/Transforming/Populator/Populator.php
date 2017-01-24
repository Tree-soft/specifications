<?php

namespace Mildberry\Specifications\Transforming\Populator;

use Illuminate\Contracts\Container\Container;
use Illuminate\Pipeline\Pipeline;
use Mildberry\Specifications\Exceptions\PopulatorException;
use Mildberry\Specifications\Generators\TypeExtractor;
use Mildberry\Specifications\Schema\LaravelFactory;
use Exception;
use Mildberry\Specifications\Transforming\Populator\Fillers\FillerInterface;
use Illuminate\Contracts\Config\Repository as Config;
use Mildberry\Specifications\Transforming\Populator\Fillers\SetterFiller;
use Closure;
use Mildberry\Specifications\Transforming\Populator\Resolvers\AbstractResolver;
use Rnr\Resolvers\Interfaces\ConfigAwareInterface;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ConfigAwareTrait;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class Populator implements ContainerAwareInterface, ConfigAwareInterface
{
    use ContainerAwareTrait;
    use ConfigAwareTrait;

    /**
     * @var LaravelFactory $factory
     */
    private $factory;

    /**
     * @var string
     */
    private $namespace = '\\';

    /**
     * @var FillerInterface
     */
    private $filler;

    /**
     * Populator constructor.
     *
     * @param LaravelFactory $factory
     * @param Config $config
     * @param Container $container
     */
    public function __construct(LaravelFactory $factory, Config $config, Container $container)
    {
        $filler = $config->get('specifications.populate.filler', SetterFiller::class);

        $this->filler = $container->make($filler);

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
    public function populate($data, $schema)
    {
        $schema = $this->factory->schema($schema);

        $entity = $this->createEntity($schema, $data);

        $pipeline = new Pipeline();

        $resolvers = $this->createResolvers($schema, $data);

        foreach (array_keys((array) $schema->properties) as $property) {
            try {
                $value = $pipeline
                    ->send($property)
                    ->through($resolvers)
                    ->then(function () use ($property) {
                        $e = new PopulatorException('Cannot populate object');

                        throw $e;
                    });

                $this->filler->fill($entity, $property, $value);
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
     * @throws Exception
     *
     * @return mixed
     */
    public function createEntity($schema, $data)
    {
        $typeExtractor = new TypeExtractor();

        $typeExtractor
            ->setNamespace($this->namespace);

        $types = $typeExtractor->extract($schema);

        if (!is_array($types)) {
            $class = $types;
        } else {
            throw new Exception('Polymorphic types is not implemented.');
        }

        return new $class();
    }

    /**
     * @param object $schema
     * @param object $data
     *
     * @return array
     */
    protected function createResolvers($schema, $data): array
    {
        $resolvers = $this->config->get('specifications.populate.resolvers', []);

        return array_map(function ($config) use ($schema, $data) {
            return $this->wrapResolver($config, $schema, $data);
        }, $resolvers);
    }

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
            ->setPopulator($this);

        return function ($property, $next) use ($resolver) {
            return $resolver->resolve($property, $next);
        };
    }
}
