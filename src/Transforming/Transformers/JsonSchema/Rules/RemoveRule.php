<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;

use Mildberry\Specifications\Support\DeepCopy\Filters\RemovePropertyFilter;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class RemoveRule extends AbstractRule
{
    public function configure()
    {
        $this->filter = new RemovePropertyFilter();
    }
}
