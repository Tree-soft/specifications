<?php

namespace Mildberry\Specifications\Transforming\Transformers;

use DeepCopy\DeepCopy;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class CopyTransformer extends AbstractTransformer
{
    /**
     * @param mixed $from
     * @param mixed|null $to
     *
     * @return mixed
     */
    public function transform($from, $to = null)
    {
        $copier = new DeepCopy();

        return $copier->copy($from);
    }
}
