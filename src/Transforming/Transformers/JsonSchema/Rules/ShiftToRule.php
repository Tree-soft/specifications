<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ShiftToRule extends AbstractRuleFrom
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
        $toProperty = $this->property;

        $object->{$toProperty} = $this->from->{$property};

        return $object;
    }

    public function setSpec(array $spec)
    {
        list($this->property) = $spec;

        return parent::setSpec($spec);
    }
}
