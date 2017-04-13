<?php

namespace TreeSoft\Specifications\Transforming\Transformers\JsonSchema\Transformations;

use TreeSoft\Specifications\Transforming\Transformers\ValueDescriptor;

/**
 * Class NullIfTransformation.
 */
class NullIfTransformation extends AbstractConditionTransformation
{
    /**
     * @param ValueDescriptor $from
     * @param ValueDescriptor $value
     *
     * @return bool
     */
    public function isTrue(ValueDescriptor $from, ValueDescriptor $value): bool
    {
        return !is_null($from->getValue());
    }
}
