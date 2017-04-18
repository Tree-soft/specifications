<?php

namespace TreeSoft\Specifications\Transforming\Resolvers;

use TreeSoft\Specifications\Transforming\Transformers\AbstractTransformer;
use TreeSoft\Specifications\Transforming\Transformers\CopyTransformer;

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
                ($next($from, $to));
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
            $this->isEqualArray($from, $to) &&
            $this->isEqualOneOf($from, $to);
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

    /**
     * @param mixed $from
     * @param mixed $to
     *
     * @return bool
     */
    public function isEqualOneOf($from, $to): bool
    {
        if (empty($from->oneOf) && empty($to->oneOf)) {
            return true;
        }

        if (empty($from->oneOf) || empty($to->oneOf)) {
            return false;
        }

        $fromEqual = [];
        $toEqual = [];

        foreach ($from->oneOf as $fromId => $fromType) {
            foreach ($to->oneOf as $toId => $toType) {
                if ($this->isEqualSchema($fromType, $toType)) {
                    $fromEqual[] = $fromId;
                    $toEqual[] = $toId;
                }
            }
        }

        sort($fromEqual);
        sort($toEqual);

        return
            ($fromEqual == array_keys($from->oneOf)) &&
            ($toEqual == array_keys($toEqual));
    }
}
