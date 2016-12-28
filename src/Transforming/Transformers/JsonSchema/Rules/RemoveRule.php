<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;

use Mildberry\Specifications\Support\DeepCopy\Filters\RemovePropertyFilter;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class RemoveRule extends AbstractRule
{
    /**
     * @param array $spec
     */
    public function configure(array $spec)
    {
        $this->filter = new RemovePropertyFilter();
    }
}
