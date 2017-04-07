<?php

namespace TreeSoft\Specifications\Support\Resolvers\SpecificationsNamespace;

/**
 * Class NamespaceAwareTrait.
 */
trait NamespaceAwareTrait
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @return mixed
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @param mixed $namespace
     *
     * @return $this
     */
    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }
}
