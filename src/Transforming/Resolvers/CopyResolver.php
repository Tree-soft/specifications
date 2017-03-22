<?php

namespace Mildberry\Specifications\Transforming\Resolvers;

use Mildberry\Specifications\Transforming\Transformers\AbstractTransformer;
use Mildberry\Specifications\Transforming\Transformers\CopyTransformer;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class CopyResolver extends AbstractResolver
{
    /**
     * @param string $from
     * @param string $to
     * @param callable $next
     *
     * @return AbstractTransformer
     */
    public function resolve($from, $to, $next): AbstractTransformer
    {
        return
            ($this->isEqualSchema($from, $to)) ?
                ($this->container->make(CopyTransformer::class)) :
                ($this->next($from, $to, $next));
    }

    /**
     * @param mixed $from
     * @param mixed $to
     *
     * @return bool
     */
    protected function isEqualSchema($from, $to): bool
    {
        if (is_string($from)) {
            $from = (object) [
                'type' => $from,
            ];
        }

        if (is_string($to)) {
            $to = (object) [
                'type' => $to,
            ];
        }

        return
            (($from->type ?? null) == ($to->type ?? null)) &&
            (($from->id ?? null) == ($to->id ?? null));
    }
}
