<?php

namespace TreeSoft\Specifications\Transforming\Transformers\JsonSchema\Transformations;

/**
 * Class ValueTransformation.
 */
abstract class ValueTransformation extends AbstractTransformation
{
    const RETURN_SELF = '&';
    /**
     * @var string
     */
    protected $field = self::RETURN_SELF;

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @param string $field
     *
     * @return $this
     */
    public function setField(string $field)
    {
        $this->field = $field;

        return $this;
    }
}
