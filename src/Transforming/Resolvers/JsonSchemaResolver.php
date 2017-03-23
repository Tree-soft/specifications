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
     * @param string|object|mixed $from
     * @param string|object|mixed $to
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
                    $this->isEqual($transformation->from, $from) &&
                    $this->isEqual($transformation->to, $to);
            }
        );

        return
            (empty($key)) ?
                ($next($from, $to)) :
                ($this->createTransformer($specifications[$key]));
    }

    /**
     * @param mixed $lhs
     * @param mixed $rhs
     *
     * @return bool
     */
    public function isEqual($lhs, $rhs): bool
    {
        $rhsId = (is_string($rhs)) ? ($rhs) : ($rhs->id ?? null);
        $lhsId = (is_string($lhs)) ? ($lhs) : ($lhs->id ?? null);

        return isset($rhsId) && isset($lhsId) && ($rhsId == $lhsId);
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
