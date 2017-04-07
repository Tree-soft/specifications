<?php

namespace TreeSoft\Specifications\Transforming\Transformers;

use TreeSoft\Specifications\Exceptions\ProhibitedTransformationException;
use TreeSoft\Specifications\Generators\TypeExtractor;
use TreeSoft\Specifications\Schema\LaravelFactory;
use TreeSoft\Specifications\Transforming\TransformerFactory;

/**
 * Class ComplexSchemaTransformer.
 */
class ComplexSchemaTransformer extends AbstractTransformer
{
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

        /**
         * @var ProhibitedTransformationException $exception
         */
        $exception = $this->container->make(ProhibitedTransformationException::class);

        $exception
            ->setFrom($this->fromSchema)
            ->setTo($this->toSchema);

        throw $exception;
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
            return $schema;
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
