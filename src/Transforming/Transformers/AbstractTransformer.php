<?php

namespace Mildberry\Specifications\Transforming\Transformers;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class AbstractTransformer
{
    /**
     * @param mixed $from
     * @param mixed|null $to
     *
     * @return mixed
     */
    abstract public function transform($from, $to = null);
}
