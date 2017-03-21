<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema;

use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Transformations\AbstractTransformation;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Transformations\ValueExtractor;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Transformations\ValuePopulator;

/**
 * Class Rule.
 */
class Rule
{
    /**
     * @var string|null
     */
    private $from = ValueExtractor::RETURN_SELF;

    /**
     * @var array|AbstractTransformation[]
     */
    private $transformations = [];

    /**
     * @var string|null
     */
    private $to = ValuePopulator::RETURN_SELF;

    /**
     * @return null|string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param null|string $from
     *
     * @return $this
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return array|AbstractTransformation[]
     */
    public function getTransformations(): array
    {
        return $this->transformations;
    }

    /**
     * @param array|AbstractTransformation[] $transformations
     *
     * @return $this
     */
    public function setTransformations(array $transformations)
    {
        $this->transformations = $transformations;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param null|string $to
     *
     * @return $this
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }
}
