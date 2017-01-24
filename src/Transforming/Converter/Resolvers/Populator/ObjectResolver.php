<?php

namespace Mildberry\Specifications\Transforming\Converter\Resolvers\Populator;

use Mildberry\Specifications\Transforming\Converter\Resolvers\AbstractResolver;
use Mildberry\Specifications\Transforming\Converter\Populator;
use Mildberry\Specifications\Transforming\Converter\Resolvers\Traits\ObjectResolverTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ObjectResolver extends AbstractResolver
{
    use ObjectResolverTrait;

    /**
     * @param string $property
     *
     * @return mixed
     */
    public function getValue(string $property)
    {
        /**
         * @var Populator $populator
         */
        $populator = $this->container->make(Populator::class);

        $populator
            ->setNamespace($this->converter->getNamespace());

        return $populator->convert(
            $this->data->{$property}, $this->schema->properties->{$property}
        );
    }
}
