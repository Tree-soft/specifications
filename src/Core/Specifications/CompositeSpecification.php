<?php

namespace TreeSoft\Specifications\Core\Specifications;

/**
 * Class CompositeSpecification.
 */
class CompositeSpecification implements FilterSpecification
{
    /**
     * @var FilterSpecification[]
     */
    private $specifications;

    /**
     * @return FilterSpecification[]
     */
    public function getSpecifications(): array
    {
        return $this->specifications;
    }

    /**
     * @param FilterSpecification[] $specifications
     *
     * @return $this
     */
    public function setSpecifications(array $specifications)
    {
        $this->specifications = $specifications;

        return $this;
    }

    /**
     * @param FilterSpecification $specification
     *
     * @return $this
     */
    public function add(FilterSpecification $specification)
    {
        $this->specifications[] = $specification;

        return $this;
    }
}
