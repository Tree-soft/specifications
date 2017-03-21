<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Transformations;

use Mildberry\Specifications\Transforming\TransformerFactory;
use Mildberry\Specifications\Transforming\Transformers\ValueDescriptor;
use RuntimeException;

/**
 * Class ValuePopulator.
 */
class ValuePopulator extends ValueTransformation
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
            $simpleValue = $value->getValue();

            $value = isset($simpleValue) ? ($value) : ($this->convert($from, $value->getSchema()));
        } elseif (is_object($value)) {
            $objectValue = $value->getValue();

            if (!property_exists($objectValue, $this->field)) {
                $objectValue->{$this->field} = $this->convert(
                    $from, $value->getSchema()->properies->{$this->field}
                );
            }
        } else {
            throw new RuntimeException('Cannot extract value');
        }

        return $value;
    }

    /**
     * @param ValueDescriptor $from
     * @param mixed|object $schema
     *
     * @return ValueDescriptor
     */
    protected function convert(ValueDescriptor $from, $schema): ValueDescriptor
    {
        /**
         * @var TransformerFactory $factory
         */
        $factory = $this->container->make(TransformerFactory::class);

        $transformer = $factory->create($from->getSchema()->id, $schema->id);

        $value = new ValueDescriptor();

        $value
            ->setValue($transformer->transform($from->getValue()))
            ->setSchema($schema);

        return $value;
    }
}
