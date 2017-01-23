<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;

use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Matcher\MatcherInterface;

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
     * @var array
     */
    protected $spec;

    /**
     * @var MatcherInterface
     */
    protected $matcher;

    /**
     * @param string $property
     * @param object $spec
     * @param mixed $object
     *
     * @return mixed
     */
    public function apply(string $property, $spec, $object) {
        if ($this->matcher->match($property, $spec)) {
            return $this->innerApply($property, $spec, $object);
        }

        return $object;
    }

    /**
     * @param string $property
     * @param object $spec
     * @param object $object
     *
     * @return object
     */
    abstract protected function innerApply(string $property, $spec, $object);

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
     * @return MatcherInterface
     */
    public function getMatcher(): MatcherInterface
    {
        return $this->matcher;
    }

    /**
     * @param MatcherInterface $matcher
     * @return $this
     */
    public function setMatcher(MatcherInterface $matcher)
    {
        $this->matcher = $matcher;

        return $this;
    }
}
