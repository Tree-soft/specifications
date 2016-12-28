<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;

use DeepCopy\DeepCopy;
use DeepCopy\Filter\Filter;
use DeepCopy\Matcher\Matcher;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class AbstractRule
{
    /**
     * @var mixed
     */
    protected $to;

    /**
     * @var Matcher
     */
    protected $matcher;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @param DeepCopy $copier
     */
    public function apply(DeepCopy $copier)
    {
        $copier->addFilter($this->filter, $this->matcher);
    }

    /**
     * @param array $spec
     */
    public function configure(array $spec)
    {
    }

    /**
     * @param mixed $object
     * @param string $property
     *
     * @return mixed
     */
    public function afterCopy($object, string $property)
    {
        return $object;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param mixed $to
     *
     * @return $this
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @return Matcher
     */
    public function getMatcher(): Matcher
    {
        return $this->matcher;
    }

    /**
     * @param Matcher $matcher
     *
     * @return $this
     */
    public function setMatcher(Matcher $matcher)
    {
        $this->matcher = $matcher;

        return $this;
    }
}
