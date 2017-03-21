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
    /**
     * @var string
     */
    private $fromSchema;

    /**
     * @var string
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

        throw new ProhibitedTransformationException(
            "Transformation '{$this->fromSchema}' to '{$this->toSchema}' is prohibited"
        );
    }

    /**
     * @param string $type
     *
     * @return array
     */
    protected function prepareSchema(string $type): array
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
     * @return string
     */
    public function getFromSchema(): string
    {
        return $this->fromSchema;
    }

    /**
     * @param string $fromSchema
     *
     * @return $this
     */
    public function setFromSchema(string $fromSchema)
    {
        $this->fromSchema = $fromSchema;

        return $this;
    }

    /**
     * @return string
     */
    public function getToSchema(): string
    {
        return $this->toSchema;
    }

    /**
     * @param string $toSchema
     *
     * @return $this
     */
    public function setToSchema(string $toSchema)
    {
        $this->toSchema = $toSchema;

        return $this;
    }
}