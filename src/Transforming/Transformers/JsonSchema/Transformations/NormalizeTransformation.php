<?php

namespace TreeSoft\Specifications\Transforming\Transformers\JsonSchema\Transformations;

use TreeSoft\Specifications\Transforming\Transformers\ValueDescriptor;

/**
 * Class NormalizeTransformation.
 */
class NormalizeTransformation extends AbstractTransformation
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
        $normalized = new ValueDescriptor();

        $normalized
            ->setValue(array_values($from->getValue()))
            ->setSchema($from->getSchema());

        return $next($normalized, $value);
    }
}
