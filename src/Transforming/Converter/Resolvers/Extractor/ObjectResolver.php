<?php

namespace Mildberry\Specifications\Transforming\Converter\Resolvers\Extractor;

use Mildberry\Specifications\Transforming\Converter\Extractor;
use Mildberry\Specifications\Transforming\Converter\Resolvers\Traits\ObjectResolverTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ObjectResolver extends ExtractorResolver
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
         * @var Extractor $extractor
         */
        $extractor = $this->container->make(Extractor::class);

        $extractor
            ->setNamespace($this->converter->getNamespace());

        $method = $this->getMethod($property);

        return $extractor->convert(
            $this->data->{$method}(), $this->schema->properties->{$property}
        );
    }
}
