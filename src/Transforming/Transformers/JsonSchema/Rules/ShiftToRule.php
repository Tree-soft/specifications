<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ShiftToRule extends AbstractRuleFrom
{
    /**
     * @param string $property
     * @param object $object
     *
     * @return object
     */
    protected function innerApply(string $property, $object)
    {
        $toProperty = $this->spec[0];

        $object->{$toProperty} = $this->from->{$property};

        return $object;
    }
}
