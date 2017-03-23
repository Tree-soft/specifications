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
    public function isEqualSchema($from, $to): bool
    {
        if (is_string($from)) {
            $from = (object) [
                'type' => $from,
                'id' => null,
            ];
        }

        if (is_string($to)) {
            $to = (object) [
                'type' => $to,
                'id' => null,
            ];
        }
        $from = $this->sanitize($from);
        $to = $this->sanitize($to);

        return
            $this->isEqualId($from, $to) &&
            $this->isEqualType($from, $to) &&
            $this->isEqualArray($from, $to);
    }

    /**
     * @param mixed $object
     *
     * @return mixed
     */
    protected function sanitize($object)
    {
        $sanitizedObject = clone $object;
        $sanitizedObject->id = $sanitizedObject->id ?? null;
        $sanitizedObject->type = $sanitizedObject->type ?? null;

        return $sanitizedObject;
    }

    /**
     * @param mixed $from
     * @param mixed $to
     *
     * @return bool
     */
    public function isEqualId($from, $to): bool
    {
        return $from->id == $to->id;
    }

    /**
     * @param mixed $from
     * @param mixed $to
     *
     * @return bool
     */
    public function isEqualType($from, $to): bool
    {
        return $from->type == $to->type;
    }

    /**
     * @param mixed $from
     * @param mixed $to
     *
     * @return bool
     */
    public function isEqualArray($from, $to): bool
    {
        if (($from->type != 'array') || ($to->type != 'array') || empty($to->items)) {
            return true;
        }

        $from = $this->sanitize($from->items ?? (object) []);
        $to = $this->sanitize($to->items);

        return $this->isEqualSchema($from, $to);
    }
}
