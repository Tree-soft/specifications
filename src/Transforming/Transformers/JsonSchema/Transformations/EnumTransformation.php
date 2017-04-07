<?php

namespace TreeSoft\Specifications\Transforming\Transformers\JsonSchema\Transformations;

use TreeSoft\Specifications\Transforming\TransformerFactory;
use TreeSoft\Specifications\Transforming\Transformers\ValueDescriptor;

/**
 * Class EnumTransformation.
 */
abstract class EnumTransformation extends AbstractTransformation
{
    /**
     * @return mixed
     */
    abstract public function getSchema();

    /**
     * @param $value
     *
     * @return mixed
     */
    abstract public function getEnum($value);

    /**
     * @param ValueDescriptor $from
     * @param ValueDescriptor $value
     * @param callable $next
     *
     * @return mixed
     */
    public function apply(ValueDescriptor $from, ValueDescriptor $value, $next): ValueDescriptor
    {
        /**
         * @var TransformerFactory $factory
         */
        $factory = $this->container->make(TransformerFactory::class);

        $schema = $this->getSchema();

        $transformer = $factory->create($from->getSchema(), $schema);

        $enum = new ValueDescriptor();

        $enum
            ->setValue(
                $this->getEnum($transformer->transform($from->getValue()))
            )
            ->setSchema($schema);

        return $next($enum, $value);
    }
}
