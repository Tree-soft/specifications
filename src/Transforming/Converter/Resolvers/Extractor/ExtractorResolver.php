<?php

namespace Mildberry\Specifications\Transforming\Converter\Resolvers\Extractor;

use Mildberry\Specifications\Transforming\Converter\Resolvers\AbstractResolver;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class ExtractorResolver extends AbstractResolver
{
    /**
     * @param string $field
     *
     * @return string
     */
    public function getMethod(string $field): string
    {
        $property = ucfirst($field);

        return "get{$property}";
    }
}
