<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class IgnoreRule extends AbstractRuleFrom
{
    /**
     * @param string $property
     * @param object $object
     *
     * @return object|void @mixed
     */
    public function innerApply(string $property, $object)
    {
        if (property_exists($this->to, $property)) {
            $object->{$property} = $this->to->{$property};
        } else {
            unset($object->{$property});
        }
    }
}
