<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Transformations;

use Mildberry\Specifications\Transforming\Transformers\ValueDescriptor;
use RuntimeException;

/**
 * Class ValueExtractor.
 */
class ValueExtractor extends ValueTransformation
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
        if ($this->field == self::RETURN_SELF) {
            $extracted = clone $from;
        } elseif (is_object($from)) {
            $extracted = new ValueDescriptor();

            $extracted
                ->setValue($from->getValue()->{$this->field})
                ->setSchema($from->getSchema()->properties->{$this->field});
        } else {
            throw new RuntimeException('Cannot extract value');
        }

        return $next($extracted, $value);
    }
}
