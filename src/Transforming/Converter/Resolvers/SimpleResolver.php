<?php

namespace Mildberry\Specifications\Transforming\Converter\Resolvers;

use Mildberry\Specifications\Generators\TypeExtractor;

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

        $extractor = new TypeExtractor();

        $extractor
            ->setNamespace($this->getConverter()->getNamespace());

        $type = $extractor->extract($schema);

        return !is_array($type) && in_array($type, ['string', 'bool', 'number', 'int']);
    }
}
