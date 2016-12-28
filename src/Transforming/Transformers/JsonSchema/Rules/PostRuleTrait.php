<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;

use DeepCopy\Filter\Filter;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
trait PostRuleTrait
{
    /**
     * @return Filter
     */
    abstract public function getFilter(): Filter;

    /**
     * @param mixed $object
     * @param string $property
     *
     * @return mixed
     */
    public function afterCopy($object, string $property)
    {
        $this->getFilter()->apply($object, $property, null);

        return $object;
    }
}
