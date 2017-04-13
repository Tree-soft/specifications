<?php

namespace TreeSoft\Specifications\Transforming\Transformers\JsonSchema\Transformations;

use TreeSoft\Specifications\Transforming\Transformers\ValueDescriptor;

/**
 * Class AbstractConditionTransformation.
 */
abstract class AbstractConditionTransformation extends AbstractTransformation
{
    /**
     * @param ValueDescriptor $from
     * @param ValueDescriptor $value
     * @param callable $next
     *
     * @return mixed
     */
    public function apply(ValueDescriptor $from, ValueDescriptor $value, $next): ValueDescriptor
    {
        return ($this->isTrue($from, $value)) ? ($next($from, $value)) : ($from);
    }

    /**
     * @param ValueDescriptor $from
     * @param ValueDescriptor $value
     *
     * @return bool
     */
    abstract public function isTrue(ValueDescriptor $from, ValueDescriptor $value): bool;
}
