<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Transformations;

use Mildberry\Specifications\Transforming\Transformers\ValueDescriptor;

/**
 * Class DefaultTransformation.
 */
class DefaultTransformation extends AbstractTransformation
{
    /**
     * @var mixed
     */
    private $default;

    /**
     * @var object|mixed
     */
    private $schema;

    /**
     * @param ValueDescriptor $from
     * @param ValueDescriptor $value
     * @param callable $next
     *
     * @return mixed
     */
    public function apply(ValueDescriptor $from, ValueDescriptor $value, $next): ValueDescriptor
    {
        $fromValue = $from->getValue();
        $default = null;

        if (is_null($fromValue)) {
            $default = clone $from;

            $default
                ->setValue($fromValue ?? $this->default)
                ->setSchema($this->schema ?? $value->getSchema());
        }

        return $next($default ?? $from, $value);
    }

    /**
     * @param array $config
     */
    public function configure(array $config)
    {
        if (count($config) == 1) {
            $config[] = null;
        }

        list($this->default, $this->schema) = $config;
    }
}
