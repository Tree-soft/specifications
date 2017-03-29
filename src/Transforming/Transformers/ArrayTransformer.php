<?php

namespace Mildberry\Specifications\Transforming\Transformers;

use Mildberry\Specifications\Exceptions\ProhibitedTransformationException;
use Mildberry\Specifications\Transforming\TransformerFactory;

/**
 * Class ArrayTransformer.
 */
class ArrayTransformer extends AbstractTransformer
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
     * @param array $from
     * @param array $to
     *
     * @throws ProhibitedTransformationException
     *
     * @return mixed
     */
    public function transform($from, $to = null)
    {
        if (!is_array($from)) {
            /**
             * @var ProhibitedTransformationException $exception
             */
            $exception = $this->container->make(ProhibitedTransformationException::class);

            $exception
                ->setFrom($from)
                ->setTo($to);

            throw $exception;
        }

        /**
         * @var TransformerFactory $factory
         */
        $factory = $this->container->make(TransformerFactory::class);
        $defaultSchema = (object) [];

        return $to ?? array_map(function ($value) use ($factory, $defaultSchema) {
            $fromSchema = $this->fromSchema ?? $this->toSchema ?? $defaultSchema;
            $toSchema = $this->toSchema ?? $this->fromSchema ?? $defaultSchema;

            $transformer = $factory->create($fromSchema, $toSchema);

            return $transformer->transform($value);
        }, $from);
    }

    /**
     * @return mixed|object|string
     */
    public function getFromSchema()
    {
        return $this->fromSchema;
    }

    /**
     * @param mixed|object|string $fromSchema
     *
     * @return $this
     */
    public function setFromSchema($fromSchema)
    {
        $this->fromSchema = $fromSchema;

        return $this;
    }

    /**
     * @return mixed|object|string
     */
    public function getToSchema()
    {
        return $this->toSchema;
    }

    /**
     * @param mixed|object|string $toSchema
     *
     * @return $this
     */
    public function setToSchema($toSchema)
    {
        $this->toSchema = $toSchema;

        return $this;
    }
}
