<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ShiftFromRule extends AbstractRuleTo
{
    /**
     * @param string $property
     * @param object $object
     *
     * @return object
     */
    protected function innerApply(string $property, $object)
    {
        $fromProperty = $this->spec[0];

        $object->{$property} = $this->from->{$fromProperty};

        return $object;
    }
}
