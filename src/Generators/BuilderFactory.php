<?php

namespace TreeSoft\Specifications\Generators;

use TreeSoft\Specifications\Exceptions\UnknownTypeException;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class BuilderFactory implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var array|AbstractBuilder[]
     */
    protected $types = [];

    /**
     * @var TypeExtractor
     */
    protected $extractor;

    /**
     * @param object $schema
     *
     * @throws UnknownTypeException
     *
     * @return AbstractBuilder
     */
    public function create($schema)
    {
        $schema = $this->extractor->extendSchema($schema);

        if (empty($schema->type) || empty($this->types[$schema->type])) {
            $exception = new UnknownTypeException('Unknown type of scheme');
            $exception
                ->setSchema($schema);

            throw $exception;
        }

        $class = $this->types[$schema->type];

        /**
         * @var AbstractBuilder $builder
         */
        $builder = $this->container->make($class);

        $builder
            ->setSchema($schema)
            ->setExtractor($this->extractor);

        return $builder;
    }

    /**
     * @return TypeExtractor
     */
    public function getExtractor(): TypeExtractor
    {
        return $this->extractor;
    }

    /**
     * @param TypeExtractor $extractor
     *
     * @return $this
     */
    public function setExtractor(TypeExtractor $extractor)
    {
        $this->extractor = $extractor;

        return $this;
    }
}
