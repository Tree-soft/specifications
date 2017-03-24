<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Transformations;

use Mildberry\Specifications\Transforming\TransformerFactory;
use Mildberry\Specifications\Transforming\Transformers\ValueDescriptor;

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
        } else {
            $objectValue = $value->getValue() ?? (object) [];

            if ((isset($this->field) && $this->field != '') && !property_exists($objectValue, $this->field)) {
                $objectValue->{$this->field} = $this->convert(
                    $from, $value->getSchema()->properties->{$this->field}
                )->getValue();
            }

            $value->setValue($objectValue);
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

        $transformer = $factory->create($from->getSchema(), $schema);

        $value = new ValueDescriptor();

        $value
            ->setValue($transformer->transform($from->getValue()))
            ->setSchema($schema);

        return $value;
    }
}
