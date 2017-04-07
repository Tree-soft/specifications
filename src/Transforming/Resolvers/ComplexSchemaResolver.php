<?php

namespace TreeSoft\Specifications\Transforming\Resolvers;

use TreeSoft\Specifications\Generators\TypeExtractor;
use TreeSoft\Specifications\Schema\LaravelFactory;
use TreeSoft\Specifications\Transforming\Transformers\AbstractTransformer;
use TreeSoft\Specifications\Transforming\Transformers\ComplexSchemaTransformer;

/**
 * Class ComplexSchemaResolver.
 */
class ComplexSchemaResolver extends AbstractResolver
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
            ($this->isComplexType($from) || $this->isComplexType($to)) ?
                ($this->createTransformer($from, $to)) :
                ($next($from, $to));
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function isComplexType($type): bool
    {
        /**
         * @var LaravelFactory $factory
         */
        $factory = $this->container->make(LaravelFactory::class);

        /**
         * @var TypeExtractor $extractor
         */
        $extractor = $this->container->make(TypeExtractor::class);

        return
            $extractor->isSchema($type) &&
            is_array($extractor->extract($factory->schema($type)));
    }

    /**
     * @param string|object|mixed $from
     * @param string|object|mixed $to
     *
     * @return ComplexSchemaTransformer
     */
    public function createTransformer($from, $to): ComplexSchemaTransformer
    {
        /**
         * @var ComplexSchemaTransformer $transformer
         */
        $transformer = $this->container->make(ComplexSchemaTransformer::class);

        $transformer
            ->setFromSchema($from)
            ->setToSchema($to);

        return $transformer;
    }
}
