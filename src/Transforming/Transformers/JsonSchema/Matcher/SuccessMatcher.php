<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Matcher;


/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class SuccessMatcher implements MatcherInterface
{
    /**
     * @param string $property
     * @param object $spec
     *
     * @return bool
     */
    public function match(string $property, $spec): bool
    {
        return true;
    }
}