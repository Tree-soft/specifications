<?php

namespace TreeSoft\Specifications\Core\Specifications\Filters;

use TreeSoft\Specifications\Core\Specifications\FilterSpecification;

/**
 * Class NotSpecification.
 */
class NotSpecification implements FilterSpecification
{
    /**
     * @var FilterSpecification
     */
    private $specification;

    /**
     * @return FilterSpecification
     */
    public function getSpecification(): FilterSpecification
    {
        return $this->specification;
    }

    /**
     * @param FilterSpecification $specification
     *
     * @return $this
     */
    public function setSpecification(FilterSpecification $specification)
    {
        $this->specification = $specification;

        return $this;
    }
}
