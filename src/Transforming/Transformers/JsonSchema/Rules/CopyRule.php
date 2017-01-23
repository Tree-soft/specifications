<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;


/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class CopyRule extends AbstractRuleFrom
{
    /**
     * @param string $property
     * @param object $spec
     * @param object $object
     *
     * @return @mixed
     */
    protected function innerApply(string $property, $spec, $object)
    {
        $value =
            (property_exists($this->to, $property)) ?
                ($this->to->{$property}) :
                ($this->from->{$property} ?? null);

        if (!empty($value)) {
            $object->{$property} = $value;
        }

        return $object;
    }
}