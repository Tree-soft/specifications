<?php

namespace TreeSoft\Specifications\Transforming\Converter\Resolvers;

use TreeSoft\Specifications\Generators\TypeExtractor;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class SimpleResolver extends AbstractResolver
{
    /**
     * @param mixed $data
     * @param callable $next
     *
     * @return mixed
     */
    public function resolve($data, $next)
    {
        return ($this->isSimpleType()) ? ($data) : ($next($data));
    }

    /**
     * @return bool
     */
    public function isSimpleType(): bool
    {
        $schema = $this->getSchema();

        if (!isset($schema->type)) {
            return false;
        }

        /**
         * @var TypeExtractor $extractor
         */
        $extractor = $this->container->make(TypeExtractor::class);

        $type = $extractor->extract($schema);

        return !is_array($type) && in_array($type, ['string', 'bool', 'number', 'int']) &&
            !$this->shouldBeIgnored($schema);
    }

    /**
     * @param $schema
     *
     * @return bool
     */
    public function shouldBeIgnored($schema): bool
    {
        return isset($schema->id) && in_array($schema->id, [
            'schema://common/datetime',
        ]);
    }
}
