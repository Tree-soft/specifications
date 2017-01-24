<?php

namespace Mildberry\Specifications\Transforming\Transformers\SimpleType\Casters;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class AbstractCaster
{
    /**
     * @var string
     */
    private $fromType;

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    abstract public function cast($value);

    /**
     * @return string
     */
    public function getFromType(): string
    {
        return $this->fromType;
    }

    /**
     * @param string $fromType
     *
     * @return $this
     */
    public function setFromType(string $fromType)
    {
        $this->fromType = $fromType;

        return $this;
    }
}
