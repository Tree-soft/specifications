<?php

namespace Mildberry\Specifications\Transforming\Converter\Resolvers;

use Mildberry\Specifications\Transforming\Converter\Converter;

use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class AbstractResolver implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var Converter
     */
    protected $converter;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var object
     */
    protected $schema;

    /**
     * @var object
     */
    protected $data;

    /**
     * @param string $property
     * @param callable $next
     *
     * @return mixed
     */
    abstract public function resolve(string $property, $next);

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
     * @return object
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param object $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return Converter
     */
    public function getConverter(): Converter
    {
        return $this->converter;
    }

    /**
     * @param Converter $converter
     *
     * @return $this
     */
    public function setConverter(Converter $converter)
    {
        $this->converter = $converter;

        return $this;
    }
}
