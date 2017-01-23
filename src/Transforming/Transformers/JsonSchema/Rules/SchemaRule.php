<?php

namespace Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;
use DeepCopy\DeepCopy;
use DeepCopy\TypeFilter\TypeFilter;


/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class SchemaRule extends AbstractTypeRule
{
    /**
     * @var object
     */
    private $schemaFrom;

    /**
     * @var object
     */
    private $schemaTo;

    /**
     * @var TypeFilter
     */
    protected $filter;

    public function configure()
    {
    }

    /**
     * @return object
     */
    public function getSchemaFrom(): object
    {
        return $this->schemaFrom;
    }

    /**
     * @param object $schemaFrom
     *
     * @return $this
     */
    public function setSchemaFrom(object $schemaFrom)
    {
        $this->schemaFrom = $schemaFrom;

        return $this;
    }

    /**
     * @return object
     */
    public function getSchemaTo(): object
    {
        return $this->schemaTo;
    }

    /**
     * @param object $schemaTo
     *
     * @return $this
     */
    public function setSchemaTo(object $schemaTo)
    {
        $this->schemaTo = $schemaTo;

        return $this;
    }
}