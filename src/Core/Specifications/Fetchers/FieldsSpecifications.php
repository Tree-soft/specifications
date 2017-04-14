<?php

namespace TreeSoft\Specifications\Core\Specifications\Fetchers;

use TreeSoft\Specifications\Core\Specifications\FetchSpecification;

/**
 * Class FieldsSpecifications.
 */
class FieldsSpecifications implements FetchSpecification
{
    /**
     * @var string[]
     */
    private $fields;

    /**
     * @return \string[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param string[] $fields
     *
     * @return $this
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;

        return $this;
    }
}
