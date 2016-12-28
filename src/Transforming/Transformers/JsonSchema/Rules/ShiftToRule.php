<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;

use Mildberry\Specifications\Support\DeepCopy\Filters\ShiftPropertyFilter;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ShiftToRule extends AbstractRule
{
    public function configure()
    {
        $this->filter = new ShiftPropertyFilter();

        $this->filter
            ->setTo($this->spec[0]);
    }
}
