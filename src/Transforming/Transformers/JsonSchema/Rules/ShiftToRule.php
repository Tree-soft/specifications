<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;

use Mildberry\Specifications\Support\DeepCopy\Filters\ShiftPropertyFilter;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ShiftToRule extends AbstractRuleFrom
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
        $toProperty = $this->spec[0];

        $object->{$toProperty} = $this->from->{$property};

        return $object;
    }

    public function configure()
    {
        $this->filter = new ShiftPropertyFilter();

        $this->filter
            ->setTo($this->spec[0]);
    }
}
