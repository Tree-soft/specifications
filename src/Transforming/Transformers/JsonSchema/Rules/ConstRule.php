<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;

use Mildberry\Specifications\Support\DeepCopy\Filters\ConstantPropertyFilter;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ConstRule extends AbstractRule implements PostRuleInterface
{
    use PostRuleTrait;

    public function configure()
    {
        $this->filter = new ConstantPropertyFilter();

        $this->filter
            ->setValue($this->spec[0]);
    }
}
