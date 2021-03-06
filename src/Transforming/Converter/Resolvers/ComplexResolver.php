<?php

namespace TreeSoft\Specifications\Transforming\Converter\Resolvers;

use TreeSoft\Specifications\Exceptions\ComplexPopulatorException;
use TreeSoft\Specifications\Exceptions\PopulatorException;
use TreeSoft\Specifications\Generators\TypeExtractor;
use TreeSoft\Specifications\Transforming\Converter\Converter;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ComplexResolver extends AbstractResolver
{
    /**
     * @param mixed $data
     * @param callable $next
     *
     * @return mixed
     */
    public function resolve($data, $next)
    {
        return ($this->isComplexType()) ? ($this->getValue($data)) : ($next($data));
    }

    /**
     * @return bool
     */
    public function isComplexType(): bool
    {
        $schema = $this->getSchema();

        /**
         * @var TypeExtractor $extractor
         */
        $extractor = $this->container->make(TypeExtractor::class);

        $types = $extractor->extract($schema);

        return is_array($types);
    }

    /**
     * @param mixed $data
     *
     * @throws ComplexPopulatorException
     *
     * @return mixed
     */
    public function getValue($data)
    {
        /**
         * @var Converter $converter
         */
        $converter = $this->getConverter();

        $schema = $this->getSchema();

        $exception = new ComplexPopulatorException();

        foreach ($schema->oneOf as $typeSchema) {
            try {
                return $converter->convert($data, $typeSchema);
            } catch (PopulatorException $e) {
                $exception->addException($e);
            }
        }

        throw $exception;
    }
}
