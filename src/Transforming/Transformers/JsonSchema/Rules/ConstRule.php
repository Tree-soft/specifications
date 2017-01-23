<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ConstRule extends AbstractRuleTo
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
        $value = $this->spec[0];

        $object->{$property} = $value;

        return $object;
    }

}
