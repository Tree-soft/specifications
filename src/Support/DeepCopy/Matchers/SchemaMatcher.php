<?php

namespace Mildberry\Specifications\Support\DeepCopy\Matchers;

use DeepCopy\TypeMatcher\TypeMatcher;
use Mildberry\Specifications\Generators\ClassBuilders\TypeExtractor;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;


/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class SchemaMatcher extends TypeMatcher implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var object
     */
    private $schemaFrom;

    /**
     * @var
     */
    private $schemaTo;

    /**
     * @var mixed
     */
    private $from;

    /**
     * @var mixed
     */
    private $to;

    /**
     * @var TypeExtractor
     */
    private $extractor;

    /**
     * SchemaMatcher constructor.
     */
    public function __construct()
    {
        parent::__construct(null);
        $this->extractor = new TypeExtractor();
    }


    /**
     * @param object $element
     * @return bool
     */
    public function matches($element)
    {
        $schemaFrom = $this->schemaFrom->properties->{$property}
        return isset($)
    }

    /**
     * @return object
     */
    public function getSchemaFrom()
    {
        return $this->schemaFrom;
    }

    /**
     * @param $schemaFrom
     *
     * @return $this
     */
    public function setSchemaFrom($schemaFrom)
    {
        $this->schemaFrom = $this->extractor->extendSchema($schemaFrom);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSchemaTo()
    {
        return $this->schemaTo;
    }

    /**
     * @param object $schemaTo
     *
     * @return $this
     */
    public function setSchemaTo($schemaTo)
    {
        $this->schemaTo = $this->extractor->extendSchema($schemaTo);

        return $this;
    }

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
}