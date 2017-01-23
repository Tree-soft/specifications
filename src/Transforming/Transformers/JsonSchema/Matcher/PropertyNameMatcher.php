<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Matcher;


/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class PropertyNameMatcher implements MatcherInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $property
     * @param object $spec
     *
     * @return bool
     */
    public function match(string $property, $spec): bool
    {
        return $property == $this->name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }
}