<?php

namespace Mildberry\Specifications\Transforming\Transformers\SimpleType\Casters;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class StringCaster extends AbstractCaster
{
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function cast($value)
    {
        return (string) $value;
    }
}
