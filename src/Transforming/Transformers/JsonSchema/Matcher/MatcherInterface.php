<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Matcher;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
interface MatcherInterface
{
    /**
     * @param string $property
     *
     * @return bool
     */
    public function match(string $property): bool;
}
