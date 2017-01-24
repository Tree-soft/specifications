<?php

namespace Mildberry\Specifications\Http\Transformers;

use Mildberry\Specifications\Http\Requests\Request;
use Mildberry\Specifications\Transforming\Populator\Populator;
use Mildberry\Specifications\Transforming\TransformerFactory;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class EntityTransformer implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var TransformerFactory
     */
    private $factory;

    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $namespace = '';

    /**
     * EntityTransformer constructor.
     *
     * @param TransformerFactory $factory
     */
    public function __construct(TransformerFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function populateFromRequest(Request $request)
    {
        $schema = $this->getSchema($this->class);

        /**
         * @var Populator $populator
         */
        $populator = $this->container->make(Populator::class);

        $populator
            ->setNamespace($this->namespace);

        $transformer = $this->factory->create($request->getDataSchema(), $schema);

        $data = $transformer->transform($request->getData());

        return $populator->populate($data, $schema);
    }

    /**
     * @param $entity
     */
    public function extractToResponse($entity)
    {
    }

    /**
     * @param string $class
     *
     * @return string
     */
    public function getSchema(string $class): string
    {
        $relativeName = str_replace($this->namespace, '', ltrim($class));

        $parts = array_map(function ($part) {
            return lcfirst($part);
        }, explode('\\', $relativeName));

        $schemaPath = trim(implode(DIRECTORY_SEPARATOR, $parts), DIRECTORY_SEPARATOR);

        return "schema://{$schemaPath}";
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
        $this->namespace = trim($namespace, '\\');

        return $this;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param string $class
     *
     * @return $this
     */
    public function setClass(string $class)
    {
        $this->class = $class;

        return $this;
    }
}
