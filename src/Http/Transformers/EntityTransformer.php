<?php

namespace Mildberry\Specifications\Http\Transformers;

use Mildberry\Specifications\Http\Requests\Request;
use Mildberry\Specifications\Transforming\Converter\Extractor;
use Mildberry\Specifications\Transforming\Converter\Populator;
use Mildberry\Specifications\Support\Transformers\EntityTransformer as ParentTransformer;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class EntityTransformer extends ParentTransformer
{
    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function populateFromRequest(Request $request)
    {
        $schema = $this->getSchema($this->class);

        /**
         * @var Populator $populator
         */
        $populator = $this->container->make(Populator::class);

        $populator
            ->setNamespace($this->namespace);

        $transformer = $this->factory->create($request->getDataSchema(), $schema);

        $data = $transformer->transform($request->getData());

        return $populator->convert($data, $schema);
    }

    /**
     * @param mixed $entity
     * @param string|object $responseSchema
     *
     * @return mixed
     */
    public function extractToResponse($entity, $responseSchema)
    {
        $schema = $this->getSchema($this->class);

        /**
         * @var Extractor $extractor
         */
        $extractor = $this->container->make(Extractor::class);

        $data = $extractor->convert($entity, $schema);

        $transformer = $this->factory->create($schema, $responseSchema);

        return $transformer->transform($data);
    }
}
