<?php

namespace TreeSoft\Specifications\Support\Transformers;

use TreeSoft\Specifications\Support\Resolvers\SpecificationsNamespace\NamespaceAwareInterface;
use TreeSoft\Specifications\Support\Resolvers\SpecificationsNamespace\NamespaceAwareTrait;
use TreeSoft\Specifications\Transforming\TransformerFactory;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class EntityTransformer implements ContainerAwareInterface, NamespaceAwareInterface
{
    use ContainerAwareTrait;
    use NamespaceAwareTrait;

    /**
     * @var TransformerFactory
     */
    protected $factory;

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
        $relativeName = str_replace(trim($this->namespace, '\\'), '', ltrim($class));

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

    /**
     * @return TransformerFactory
     */
    public function getFactory(): TransformerFactory
    {
        return $this->factory;
    }
}
