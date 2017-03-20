<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ConstRule extends AbstractRuleTo
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @param string $property
     * @param object $object
     *
     * @return object
     */
    protected function innerApply(string $property, $object)
    {
        $object->{$property} = $this->value;

        return $object;
    }

    public function setSpec(array $spec)
    {
        list($this->value) = $spec;

        return parent::setSpec($spec);
    }
}
