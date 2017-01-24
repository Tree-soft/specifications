<?php

namespace Mildberry\Specifications\Transforming\Transformers\SimpleType\Casters;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class IntegerCaster extends AbstractCaster
{
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function cast($value)
    {
        return (int) $value;
    }
}
