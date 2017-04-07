<?php

namespace TreeSoft\Specifications\Support\Resolvers\SpecificationsNamespace;

use Rnr\Resolvers\Interfaces\ConfigAwareInterface;
use Rnr\Resolvers\Resolvers\AbstractResolver;
use Rnr\Resolvers\Traits\ConfigAwareTrait;

/**
 * Class NamespaceResolver.
 */
class NamespaceResolver extends AbstractResolver implements ConfigAwareInterface
{
    use ConfigAwareTrait;

    /**
     * @param NamespaceAwareInterface $object
     */
    public function bind($object)
    {
        $object->setNamespace($this->config->get('specifications.namespace', '\\'));
    }
}
