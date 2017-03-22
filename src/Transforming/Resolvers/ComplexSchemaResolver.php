<?php

namespace Mildberry\Specifications\Transforming\Resolvers;

use Mildberry\Specifications\Generators\TypeExtractor;
use Mildberry\Specifications\Schema\LaravelFactory;
use Mildberry\Specifications\Transforming\Transformers\AbstractTransformer;
use Mildberry\Specifications\Transforming\Transformers\ComplexSchemaTransformer;

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
                ($this->next($from, $to, $next));
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
            (!is_string($type) || $extractor->isSchema($type)) &&
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
