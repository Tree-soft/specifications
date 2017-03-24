<?php

namespace Mildberry\Specifications\Transforming\Resolvers;

use Mildberry\Specifications\Generators\TypeExtractor;
use Mildberry\Specifications\Schema\LaravelFactory;
use Mildberry\Specifications\Transforming\Transformers\AbstractTransformer;
use Mildberry\Specifications\Transforming\Transformers\NullTransformer;

/**
 * Class NullResolver.
 */
class NullResolver extends AbstractResolver
{
    /**
     * @param mixed|object|string $from
     * @param mixed|object|string $to
     * @param callable $next
     *
     * @return AbstractTransformer
     */
    public function resolve($from, $to, $next): AbstractTransformer
    {
        return
            ($this->isNull($to)) ?
                ($this->container->make(NullTransformer::class)) :
                ($next($from, $to));
    }

    /**
     * @param $schema
     *
     * @return bool
     */
    public function isNull($schema): bool
    {
        /**
         * @var TypeExtractor $extractor
         */
        $extractor = $this->container->make(TypeExtractor::class);

        /**
         * @var LaravelFactory $factory
         */
        $factory = $this->container->make(LaravelFactory::class);

        $type = ($extractor->isSchema($schema)) ?
            ($extractor->extract($factory->schema($schema))) :
            ($schema);

        return $type == TypeExtractor::NULL;
    }
}
