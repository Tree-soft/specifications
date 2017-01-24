<?php

namespace Mildberry\Specifications\Transforming\Converter\Resolvers\Traits;

use Mildberry\Specifications\Generators\TypeExtractor;
use Mildberry\Specifications\Transforming\Converter\Converter;


/**
 * @author Sergei Melnikov <me@rnr.name>
 */
trait SimpleResolverTrait
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
        $schema = $this->getSchema()->properties->{$property};

        $extractor = new TypeExtractor();

        $extractor
            ->setNamespace($this->getConverter()->getNamespace());

        $type = $extractor->extract($schema);

        return !is_array($type) && in_array($type, ['string', 'bool', 'number', 'int']);
    }

    /**
     * @param $property
     *
     * @return mixed
     */
    abstract public function getValue($property);

    /**
     * @return Converter
     */
    abstract public function getConverter(): Converter;

    /**
     * @return object
     */
    abstract public function getSchema();
}