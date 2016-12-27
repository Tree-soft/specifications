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
    public function resolve(string $from, string $to, $next): AbstractTransformer
    {
        $config = $this->getConfig();

        /**
         * @var LaravelFactory $factory
         */
        $factory = $this->container->make(LaravelFactory::class);

        $schema = $factory->schema($config['schema']);

        $transformations = (array) $schema->transformations;

        $key = array_first(array_keys($transformations),
            function ($key) use ($from, $to, $transformations) {
                $transformation = $transformations[$key];

                return
                    ($transformation->from == $from) &&
                    ($transformation->to == $to);
            }
        );

        return
            (empty($key)) ?
                ($this->next($from, $to, $next)) :
                ($this->createTransformer($transformations[$key]));
    }

    /**
     * @param object $transformation
     *
     * @return JsonSchemaTransformer
     */
    protected function createTransformer($transformation): JsonSchemaTransformer
    {
        $transformer = new JsonSchemaTransformer();

        $transformer
            ->setTransformation($transformation);

        return $transformer;
    }
}
