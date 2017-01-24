<?php

namespace Mildberry\Specifications\Transforming\Transformers;

use Mildberry\Specifications\Transforming\Transformers\SimpleType\Casters\AbstractCaster;
use Mildberry\Specifications\Transforming\Transformers\SimpleType\Casters\BooleanCaster;
use Mildberry\Specifications\Transforming\Transformers\SimpleType\Casters\FloatCaster;
use Mildberry\Specifications\Transforming\Transformers\SimpleType\Casters\IntegerCaster;
use Mildberry\Specifications\Transforming\Transformers\SimpleType\Casters\StringCaster;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class SimpleTypeTransformer extends AbstractTransformer
{
    /**
     * @var string
     */
    private $fromType;

    /**
     * @var string
     */
    private $toType;

    /**
     * @var array|AbstractCaster[]
     */
    private $casters = [
        'boolean' => BooleanCaster::class,
        'number' => FloatCaster::class,
        'string' => StringCaster::class,
        'integer' => IntegerCaster::class,
    ];

    /**
     * @param mixed $from
     * @param mixed|null $to
     *
     * @return mixed
     */
    public function transform($from, $to = null)
    {
        $caster = $this->createCaster();

        return $to ?? $caster->cast($from);
    }

    /**
     * @return AbstractCaster
     */
    public function createCaster(): AbstractCaster
    {
        /**
         * @var AbstractCaster $caster
         */
        $caster = new $this->casters[$this->toType]();

        $caster
            ->setFromType($this->fromType);

        return $caster;
    }

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

    /**
     * @return string
     */
    public function getToType(): string
    {
        return $this->toType;
    }

    /**
     * @param string $toType
     *
     * @return $this
     */
    public function setToType(string $toType)
    {
        $this->toType = $toType;

        return $this;
    }
}
