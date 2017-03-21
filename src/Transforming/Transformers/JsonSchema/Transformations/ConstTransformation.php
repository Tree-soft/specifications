<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Transformations;

use Mildberry\Specifications\Transforming\Transformers\ValueDescriptor;

/**
 * Class ConstTransformation.
 */
class ConstTransformation extends AbstractTransformation
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @param ValueDescriptor $from
     * @param ValueDescriptor $value
     * @param callable $next
     *
     * @return mixed
     */
    public function apply(ValueDescriptor $from, ValueDescriptor $value, $next): ValueDescriptor
    {
    }

    /**
     * @param array $config
     */
    public function configure(array $config)
    {
        $this->value = $config[0];
    }
}
