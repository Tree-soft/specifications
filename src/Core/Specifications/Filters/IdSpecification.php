<?php

namespace TreeSoft\Specifications\Core\Specifications\Filters;

use TreeSoft\Specifications\Core\Specifications\FilterSpecification;

/**
 * Class IdSpecification.
 */
class IdSpecification implements FilterSpecification
{
    /**
     * @var int|string
     */
    private $id;

    /**
     * @param int|string $id
     *
     * @return $this
     */
    public function equalId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }
}
