<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ShiftFromRule extends AbstractRuleTo
{
    /**
     * @var string
     */
    private $property;

    /**
     * @param string $property
     * @param object $object
     *
     * @return object
     */
    protected function innerApply(string $property, $object)
    {
        $fromProperty = $this->property;

        $object->{$property} = $this->from->{$fromProperty};

        return $object;
    }

    public function setSpec(array $spec)
    {
        list($this->property) = $spec;

        return parent::setSpec($spec);
    }
}
