<?php

namespace Mildberry\Tests\Specifications\Mocks\DAL\Models;

use Mildberry\Specifications\DAL\Eloquent\Model;
use Mildberry\Tests\Specifications\Mocks\CallsTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ModelMock extends Model
{
    use CallsTrait;

    /**
     * @var bool
     */
    protected $guarded = [];

    /**
     * ModelMock constructor.
     *
     * @param string $schema
     * @param array $attributes
     */
    public function __construct(string $schema, $attributes = [])
    {
        $this->schema = $schema;

        parent::__construct($attributes);
    }

    /**
     * @param array $options
     *
     * @return bool
     */
    public function save(array $options = [])
    {
        $this->_handle(__FUNCTION__, $options);

        return true;
    }
}