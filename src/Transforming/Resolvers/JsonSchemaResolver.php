<?php

namespace Mildberry\Specifications\Transforming\Resolvers;

use Mildberry\Specifications\Schema\LaravelFactory;
use Mildberry\Specifications\Transforming\Transformers\AbstractTransformer;
use Mildberry\Specifications\Transforming\Transformers\JsonSchemaTransformer;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class JsonSchemaResolver extends AbstractResolver
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
        $config = $this->getConfig();

        /**
         * @var LaravelFactory $factory
         */
        $factory = $this->container->make(LaravelFactory::class);

        $schema = $factory->schema($config['schema']);

        $specifications = (array) $schema->transformations;

        $key = array_first(array_keys($specifications),
            function ($key) use ($from, $to, $specifications) {
                $transformation = $specifications[$key];

                return
                    ($transformation->from == $from) &&
                    ($transformation->to == $to);
            }
        );

        return
            (empty($key)) ?
                ($this->next($from, $to, $next)) :
                ($this->createTransformer($specifications[$key]));
    }

    /**
     * @param object $specification
     *
     * @return JsonSchemaTransformer
     */
    protected function createTransformer($specification): JsonSchemaTransformer
    {
        /**
         * @var JsonSchemaTransformer $transformer
         */
        $transformer = $this->container->make(JsonSchemaTransformer::class);

        $transformer
            ->setSpecification($specification);

        return $transformer;
    }
}
