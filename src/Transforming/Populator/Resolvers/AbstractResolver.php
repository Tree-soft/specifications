<?php

namespace Mildberry\Specifications\Transforming\Populator\Resolvers;

use Mildberry\Specifications\Transforming\Populator\Populator;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class AbstractResolver implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var Populator
     */
    protected $populator;

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
     * @return Populator
     */
    public function getPopulator(): Populator
    {
        return $this->populator;
    }

    /**
     * @param Populator $populator
     *
     * @return $this
     */
    public function setPopulator(Populator $populator)
    {
        $this->populator = $populator;

        return $this;
    }
}
