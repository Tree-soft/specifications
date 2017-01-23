<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ShiftFromRule extends AbstractRuleTo
{
    /**
     * @param string $property
     * @param object $spec
     * @param object $object
     *
     * @return object
     */
    protected function innerApply(string $property, $spec, $object)
    {
        $fromProperty = $this->spec[0];

        $object->{$property} = $this->from->{$fromProperty};

        return $object;
    }
}
