<?php

namespace Mildberry\Specifications\Transforming\Resolvers;

use Mildberry\Specifications\Transforming\Transformers\AbstractTransformer;
use Mildberry\Specifications\Transforming\Transformers\SimpleTypeTransformer;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class SimpleTypeResolver extends AbstractResolver
{
    /**
     * @var array|string[]
     */
    private $types = [
        'boolean', 'number', 'string', 'integer',
    ];

    /**
     * @param string $from
     * @param string $to
     * @param callable $next
     *
     * @return AbstractTransformer
     */
    public function resolve(string $from, string $to, $next): AbstractTransformer
    {
        return
            ($this->isSimpleType($from) && $this->isSimpleType($to)) ?
                ($this->createTransformer($from, $to)) : ($this->next($from, $to, $next));
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function isSimpleType(string $type): bool
    {
        return in_array($type, $this->types);
    }

    /**
     * @param string $from
     * @param string $to
     *
     * @return SimpleTypeTransformer
     */
    public function createTransformer(string $from, string $to): SimpleTypeTransformer
    {
        /**
         * @var SimpleTypeTransformer $transformer
         */
        $transformer = $this->container->make(SimpleTypeTransformer::class);

        $transformer
            ->setFromType($from)
            ->setToType($to);

        return $transformer;
    }
}
