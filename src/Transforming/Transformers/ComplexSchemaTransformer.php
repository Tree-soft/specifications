<?php

namespace Mildberry\Specifications\Transforming\Transformers;

use Mildberry\Specifications\Exceptions\ProhibitedTransformationException;
use Mildberry\Specifications\Generators\TypeExtractor;
use Mildberry\Specifications\Schema\LaravelFactory;
use Mildberry\Specifications\Transforming\TransformerFactory;

/**
 * Class ComplexSchemaTransformer.
 */
class ComplexSchemaTransformer extends AbstractTransformer
{
    const UNKNOWN_SCHEMA = 'unknown schema name';
    /**
     * @var string|object|mixed
     */
    private $fromSchema;

    /**
     * @var string|object|mixed
     */
    private $toSchema;

    /**
     * @param mixed $from
     * @param mixed|null $to
     *
     * @throws ProhibitedTransformationException
     *
     * @return mixed
     */
    public function transform($from, $to = null)
    {
        /**
         * @var TransformerFactory $factory
         */
        $factory = $this->container->make(TransformerFactory::class);

        foreach ($this->prepareSchema($this->fromSchema) as $fromSchema) {
            foreach ($this->prepareSchema($this->toSchema) as $toSchema) {
                try {
                    $transformer = $factory->create($fromSchema, $toSchema);

                    return $transformer->transform($from, $to);
                } catch (ProhibitedTransformationException $e) {
                    // If we can't transform data try next transformation
                }
            }
        }

        $fromSchema = $this->wrapSchemaName(
            $this->getSchemaName($this->fromSchema)
        );

        $toSchema = $this->wrapSchemaName(
            $this->getSchemaName($this->toSchema)
        );

        throw new ProhibitedTransformationException(
            "Transformation from {$fromSchema} to {$toSchema} is prohibited"
        );
    }

    /**
     * @param string|object|mixed $schema
     *
     * @return string|null
     */
    public function getSchemaName($schema): ?string
    {
        if (is_string($schema)) {
            return $schema;
        } elseif (is_object($schema)) {
            return $schema->id ?? $schema->type ?? null;
        }

        return null;
    }

    /**
     * @param $schema
     *
     * @return string
     */
    public function wrapSchemaName($schema): string
    {
        if (is_null($schema)) {
            return self::UNKNOWN_SCHEMA;
        }

        /**
         * @var TypeExtractor $typeExtractor
         */
        $typeExtractor = $this->container->make(TypeExtractor::class);

        return ($typeExtractor->isSchema($schema)) ? ("'{$schema}'") : ("type '{$schema}'");
    }

    /**
     * @param string $type
     *
     * @return array
     */
    protected function prepareSchema($type): array
    {
        /**
         * @var LaravelFactory $factory
         */
        $factory = $this->container->make(LaravelFactory::class);

        /**
         * @var TypeExtractor $extractor
         */
        $extractor = $this->container->make(TypeExtractor::class);

        if (!$extractor->isSchema($type)) {
            return [$type];
        }

        $schema = $extractor->extendSchema($factory->schema($type));

        return array_map(function ($schema) {
            return $schema->id ?? $schema->type;
        }, $schema->oneOf ?? [$schema]);
    }

    /**
     * @return string|object|mixed
     */
    public function getFromSchema()
    {
        return $this->fromSchema;
    }

    /**
     * @param string|object|mixed $fromSchema
     *
     * @return $this
     */
    public function setFromSchema($fromSchema)
    {
        $this->fromSchema = $fromSchema;

        return $this;
    }

    /**
     * @return string|object|mixed
     */
    public function getToSchema()
    {
        return $this->toSchema;
    }

    /**
     * @param string|object|mixed $toSchema
     *
     * @return $this
     */
    public function setToSchema($toSchema)
    {
        $this->toSchema = $toSchema;

        return $this;
    }
}
