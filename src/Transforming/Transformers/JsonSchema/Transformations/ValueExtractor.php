<?php

namespace TreeSoft\Specifications\Transforming\Transformers\JsonSchema\Transformations;

use TreeSoft\Specifications\Transforming\Transformers\ValueDescriptor;
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

            $objectValue = $from->getValue();
            $objectSchema = $from->getSchema();

            $extracted
                ->setValue(
                    (property_exists($objectValue, $this->field)) ?
                        ($from->getValue()->{$this->field}) : (null)
                )
                ->setSchema(
                    (property_exists($objectSchema->properties, $this->field)) ?
                        ($objectSchema->properties->{$this->field}) : ((object) ['type' => 'null'])
                );
        } else {
            throw new RuntimeException('Cannot extract value');
        }

        return $next($extracted, $value);
    }
}
