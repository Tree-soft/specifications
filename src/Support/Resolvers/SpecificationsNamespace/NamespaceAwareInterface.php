<?php

namespace Mildberry\Specifications\Support\Resolvers\SpecificationsNamespace;

/**
 * Interface NamespaceAwareInterface.
 */
interface NamespaceAwareInterface
{
    /**
     * @param mixed $namespace
     *
     * @return $this
     */
    public function setNamespace(string $namespace);
}
