<?php

namespace Mildberry\Specifications\Transforming\Transformers;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class JsonSchemaTransformer extends AbstractTransformer
{
    /**
     * @var object
     */
    private $transformation;

    /**
     * @param mixed $from
     * @param mixed|null $to
     *
     * @return mixed
     */
    public function transform($from, $to = null)
    {
    }

    /**
     * @return object
     */
    public function getTransformation()
    {
        return $this->transformation;
    }

    /**
     * @param object $transformation
     *
     * @return $this
     */
    public function setTransformation($transformation)
    {
        $this->transformation = $transformation;

        return $this;
    }
}
