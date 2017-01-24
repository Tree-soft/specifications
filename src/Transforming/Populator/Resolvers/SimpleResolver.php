<?php

namespace Mildberry\Specifications\Transforming\Populator\Resolvers;

use Mildberry\Specifications\Generators\TypeExtractor;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class SimpleResolver extends AbstractResolver
{
    /**
     * @param string $property
     * @param callable $next
     *
     * @return mixed
     */
    public function resolve(string $property, $next)
    {
        return ($this->isSimpleType($property)) ?
            ($this->getValue($property)) : ($next($property));
    }

    /**
     * @param string $property
     *
     * @return bool
     */
    public function isSimpleType(string $property): bool
    {
        $schema = $this->schema->properties->{$property};

        $extractor = new TypeExtractor();

        $extractor
            ->setNamespace($this->populator->getNamespace());

        $type = $extractor->extract($schema);

        return !is_array($type) && in_array($type, ['string', 'bool', 'number', 'int']);
    }

    /**
     * @param $property
     *
     * @return mixed
     */
    public function getValue($property)
    {
        return $this->data->{$property};
    }
}
