<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;

use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Matcher\MatcherInterface;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class AbstractRule implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var mixed
     */
    protected $from;

    /**
     * @var mixed
     */
    protected $to;

    /**
     * @var object|mixed
     */
    protected $fromSchema;

    /**
     * @var object|mixed
     */
    protected $toSchema;

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
     * @param mixed $object
     *
     * @return mixed
     */
    public function apply(string $property, $object)
    {
        if ($this->matcher->match($property)) {
            return $this->innerApply($property, $object);
        }

        return $object;
    }

    /**
     * @param string $property
     * @param object $object
     *
     * @return object
     */
    abstract protected function innerApply(string $property, $object);

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
     *
     * @return $this
     */
    public function setMatcher(MatcherInterface $matcher)
    {
        $this->matcher = $matcher;

        return $this;
    }

    /**
     * @return mixed|object
     */
    public function getFromSchema()
    {
        return $this->fromSchema;
    }

    /**
     * @param mixed|object $fromSchema
     *
     * @return $this
     */
    public function setFromSchema($fromSchema)
    {
        $this->fromSchema = $fromSchema;

        return $this;
    }

    /**
     * @return mixed|object
     */
    public function getToSchema()
    {
        return $this->toSchema;
    }

    /**
     * @param mixed|object $toSchema
     *
     * @return $this
     */
    public function setToSchema($toSchema)
    {
        $this->toSchema = $toSchema;

        return $this;
    }
}
