<?php

namespace TreeSoft\Specifications\Transforming\Transformers;

/**
 * Class NullTransformer.
 */
class NullTransformer extends AbstractTransformer
{
    /**
     * @param mixed $from
     * @param mixed|null $to
     *
     * @return mixed
     */
    public function transform($from, $to = null)
    {
        return null;
    }
}
