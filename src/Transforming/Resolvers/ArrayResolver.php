<?php

namespace TreeSoft\Specifications\Transforming\Resolvers;

use TreeSoft\Specifications\Generators\TypeExtractor;
use TreeSoft\Specifications\Schema\LaravelFactory;
use TreeSoft\Specifications\Transforming\Transformers\AbstractTransformer;
use TreeSoft\Specifications\Transforming\Transformers\ArrayTransformer;

/**
 * Class ArrayResolver.
 */
class ArrayResolver extends AbstractResolver
{
    /**
     * @param string|object|mixed $from
     * @param string|object|mixed $to
     * @param callable $next
     *
     * @return AbstractTransformer
     */
    public function resolve($from, $to, $next): AbstractTransformer
    {
        return
            ($this->isArray($from) && $this->isArray($to)) ?
                ($this->createTransformer($from, $to)) : ($next($from, $to));
    }

    /**
     * @param mixed $type
     *
     * @return bool
     */
    public function isArray($type): bool
    {
        /**
         * @var TypeExtractor $extractor
         */
        $extractor = $this->container->make(TypeExtractor::class);

        if (!$extractor->isSchema($type)) {
            return false;
        }

        /**
         * @var LaravelFactory $factory
         */
        $factory = $this->container->make(LaravelFactory::class);

        $schema = $factory->schema($type);

        return $schema->type == TypeExtractor::ARRAY;
    }

    /**
     * @param string|object|mixed $from
     * @param string|object|mixed $to
     *
     * @return ArrayTransformer
     */
    public function createTransformer($from, $to): ArrayTransformer
    {
        /**
         * @var ArrayTransformer $transformer
         */
        $transformer = $this->container->make(ArrayTransformer::class);

        $transformer
            ->setFromSchema($from->items ?? null)
            ->setToSchema($to->items ?? null);

        return $transformer;
    }
}
