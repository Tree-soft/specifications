<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;

use Mildberry\Specifications\Support\DeepCopy\Filters\IgnorePropertyFilter;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class IgnoreRule extends AbstractRuleFrom
{
    /**
     * @param string $property
     * @param object $spec
     * @param object $object
     *
     * @return @mixed
     */
    public function innerApply(string $property, $spec, $object)
    {
        if (property_exists($this->to, $property)) {
            $object->{$property} = $this->to->{$property};
        } else {
            unset($object->{$property});
        }
    }
}
