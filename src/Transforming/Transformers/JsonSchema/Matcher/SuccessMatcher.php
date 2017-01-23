<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Matcher;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class SuccessMatcher implements MatcherInterface
{
    /**
     * @param string $property
     *
     * @return bool
     */
    public function match(string $property): bool
    {
        return true;
    }
}
