<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;

use DeepCopy\DeepCopy;
use Mildberry\Specifications\Support\DeepCopy\Filters\IgnorePropertyFilter;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class IgnoreRule extends AbstractRule
{
    /**
      * @var IgnorePropertyFilter
      */
     protected $filter;

    public function configure()
    {
        $this->filter = new IgnorePropertyFilter();
    }

    /**
     * @param DeepCopy $copier
     */
    public function apply(DeepCopy $copier)
    {
        $this->filter
            ->setValue($this->to);

        parent::apply($copier);
    }
}
