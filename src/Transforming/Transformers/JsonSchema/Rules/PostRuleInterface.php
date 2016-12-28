<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
interface PostRuleInterface
{
    /**
     * @param mixed $object
     * @param string $property
     *
     * @return mixed
     */
    public function afterCopy($object, string $property);
}
