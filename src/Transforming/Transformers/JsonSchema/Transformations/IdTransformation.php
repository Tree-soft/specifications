<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Transformations;

use Mildberry\Specifications\Transforming\Transformers\ValueDescriptor;

/**
 * Class IdTransformation.
 */
class IdTransformation extends AbstractTransformation
{
    /**
     * @var string
     */
    private $field = 'id';

    /**
     * @param ValueDescriptor $from
     * @param ValueDescriptor $value
     * @param callable $next
     *
     * @return mixed
     */
    public function apply(ValueDescriptor $from, ValueDescriptor $value, $next): ValueDescriptor
    {
        $object = new ValueDescriptor();

        $object
            ->setSchema($value->getSchema()->properties->{$this->field})
            ->setValue((object) [
                $this->field => $this->transform($from, $object)->getValue(),
            ]);

        return $next($object, $value);
    }

    /**
     * @param array $config
     */
    public function configure(array $config)
    {
        $this->field = $config[0] ?? 'id';
    }
}
