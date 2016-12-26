<?php

namespace Mildberry\Specifications\Generators;

use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class AbstractBuilder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var object
     */
    protected $schema;

    /**
     * @var AbstractGenerator
     */
    protected $generator;

    /**
     * @var TypeExtractor
     */
    protected $extractor;

    /**
     * @return string
     */
    abstract public function build(): string;

    /**
     * @return object
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * @param object $schema
     *
     * @return $this
     */
    public function setSchema($schema)
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * @return AbstractGenerator
     */
    public function getGenerator()
    {
        return $this->generator;
    }

    /**
     * @param AbstractGenerator $generator
     *
     * @return $this
     */
    public function setGenerator($generator)
    {
        $this->generator = $generator;

        return $this;
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
