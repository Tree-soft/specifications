<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;

use Mildberry\Specifications\Transforming\TransformerFactory;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class CopyRule extends AbstractRuleFrom
{
    /**
     * @param string $property
     * @param object $object
     *
     * @return mixed
     */
    protected function innerApply(string $property, $object)
    {
        if (
            !property_exists($this->fromSchema->properties, $property) ||
            !property_exists($this->toSchema->properties, $property)
        ) {
            return $object;
        }

        /**
         * @var TransformerFactory $factory
         */
        $factory = $this->container->make(TransformerFactory::class);

        $transformer = $factory->create(
            $this->getType($this->fromSchema->properties->{$property}),
            $this->getType($this->toSchema->properties->{$property})
        );

        if (
            property_exists($this->from, $property) ||
            property_exists($this->to, $property)
        ) {
            $object->{$property} = $transformer->transform(
                $this->from->{$property} ?? null,
                $this->to->{$property} ?? null
            );
        }

        return $object;
    }

    /**
     * @param mixed|object $schema
     *
     * @return string
     */
    protected function getType($schema): string
    {
        return $schema->id ?? $schema->type;
    }
}
