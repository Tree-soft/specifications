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
    protected $from;

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
     * @var array
     */
    protected $spec;

    /**
     * @param DeepCopy $copier
     */
    public function apply(DeepCopy $copier)
    {
        $copier->addFilter($this->filter, $this->matcher);
    }

    abstract public function configure();

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param mixed $from
     *
     * @return $this
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
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

    /**
     * @return array
     */
    public function getSpec(): array
    {
        return $this->spec;
    }

    /**
     * @param array $spec
     *
     * @return $this
     */
    public function setSpec(array $spec)
    {
        $this->spec = $spec;

        return $this;
    }

    /**
     * @return Filter
     */
    public function getFilter(): Filter
    {
        return $this->filter;
    }

    /**
     * @param Filter $filter
     *
     * @return $this
     */
    public function setFilter(Filter $filter)
    {
        $this->filter = $filter;

        return $this;
    }
}
