<?php

namespace TreeSoft\Specifications\Transforming\Converter\Resolvers;

use TreeSoft\Specifications\Generators\TypeExtractor;

/**
 * Class ArrayResolver.
 */
class ArrayResolver extends AbstractResolver
{
    /**
     * @param mixed $data
     * @param callable $next
     *
     * @return mixed
     */
    public function resolve($data, $next)
    {
        return ($this->isArray($data)) ? ($this->getValue($data)) : ($next($data));
    }

    /**
     * @param mixed $data
     *
     * @return bool
     */
    public function isArray($data): bool
    {
        $schema = $this->getSchema();

        /**
         * @var TypeExtractor $extractor
         */
        $extractor = $this->container->make(TypeExtractor::class);

        $types = $extractor->extract($schema);

        return is_array($data) && !is_array($types) && ($types == TypeExtractor::ARRAY);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function getValue(array $data): array
    {
        return array_map(function ($value) {
            return
                isset($this->schema->items->type) ?
                    ($this->getConverter()->convert($value, $this->schema->items)) :
                    ($value);
        }, $data);
    }
}
