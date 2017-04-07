<?php

namespace TreeSoft\Specifications\DAL\Eloquent;

use Illuminate\Database\Eloquent\Model as ParentModel;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class Model extends ParentModel
{
    /**
     * @var string
     */
    public $schema;

    /**
     * Model constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        assert(isset($this->schema));

        parent::__construct($attributes);
    }

    /**
     * @return string
     */
    public function getSchema(): string
    {
        return $this->schema;
    }
}
