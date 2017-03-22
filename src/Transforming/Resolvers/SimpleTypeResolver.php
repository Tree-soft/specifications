<?php

namespace Mildberry\Specifications\Transforming\Resolvers;

use Mildberry\Specifications\Exceptions\SchemaExtractionException;
use Mildberry\Specifications\Generators\TypeExtractor;
use Mildberry\Specifications\Schema\LaravelFactory;
use Mildberry\Specifications\Transforming\Transformers\AbstractTransformer;
use Mildberry\Specifications\Transforming\Transformers\SimpleTypeTransformer;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class SimpleTypeResolver extends AbstractResolver
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
        $fromType = $this->simplifyType($from);
        $toType = $this->simplifyType($to);

        return
            (isset($fromType) && isset($toType) && ($this->isSimpleType($fromType) && $this->isSimpleType($toType))) ?
                ($this->createTransformer($fromType, $toType)) :
                ($this->next($from, $to, $next));
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function isSimpleType(string $type): bool
    {
        $casters = $this->getConfig()['casters'] ?? [];

        return !is_array($type) && in_array($type, array_keys($casters));
    }

    /**
     * @param string|object|mixed $from
     * @param string|object|mixed $to
     *
     * @return SimpleTypeTransformer
     */
    public function createTransformer($from, $to): SimpleTypeTransformer
    {
        /**
         * @var SimpleTypeTransformer $transformer
         */
        $transformer = $this->container->make(SimpleTypeTransformer::class);

        $transformer
            ->setFromType($from)
            ->setToType($to)
            ->setCasters($this->getConfig()['casters'] ?? []);

        return $transformer;
    }

    /**
     * @param $schema
     *
     * @return string|null
     */
    protected function simplifyType($schema): ?string
    {
        /**
         * @var TypeExtractor $extractor
         */
        $extractor = $this->container->make(TypeExtractor::class);

        try {
            if ($extractor->isSchema($schema)) {
                /**
                 * @var LaravelFactory $factory
                 */
                $factory = $this->container->make(LaravelFactory::class);
                $schema = $factory->schema($schema);
            }

            $type = $extractor->extract($schema);
        } catch (SchemaExtractionException $e) {
            $type = $schema;
        }

        return (is_array($type)) ? (null) : ($extractor->mapType($type));
    }
}
