<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;

use Mildberry\Specifications\Support\DeepCopy\Filters\ConstantPropertyFilter;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ConstRule extends AbstractRule
{
    /**
     * @param array $spec
     */
    public function configure(array $spec)
    {
        $this->filter = new ConstantPropertyFilter();

        $this->filter
            ->setValue($spec[0]);
    }

    /**
     * @param mixed $object
     * @param string $property
     *
     * @return mixed
     */
    public function afterCopy($object, string $property)
    {
        $this->filter->apply($object, $property, null);

        return $object;
    }
}
