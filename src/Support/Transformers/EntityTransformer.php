<?php

namespace Mildberry\Specifications\Support\Transformers;

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
    protected $factory;
    /**
     * @var string
     */
    protected $namespace = '';
    /**
     * @var string
     */
    protected $class;

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
     * @param string $class
     *
     * @return string
     */
    public function getSchema(string $class): string
    {
        $relativeName = str_replace($this->namespace, '', ltrim($class));

        $parts = array_map(function ($part) {
            return ($this->isUpperString($part)) ? (strtolower($part)) : (lcfirst($part));
        }, explode('\\', $relativeName));

        $schemaPath = trim(implode(DIRECTORY_SEPARATOR, $parts), DIRECTORY_SEPARATOR);

        return "schema://{$schemaPath}";
    }

    /**
     * @param string $text
     *
     * @return bool
     */
    public function isUpperString(string $text): bool
    {
        return ctype_upper(preg_replace('/\d/', '', $text));
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
     * @return EntityTransformer
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
     * @return EntityTransformer
     */
    public function setClass(string $class)
    {
        $this->class = $class;

        return $this;
    }
}
