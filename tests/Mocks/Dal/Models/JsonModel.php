<?php

namespace TreeSoft\Tests\Specifications\Mocks\Dal\Models;

use TreeSoft\Specifications\DAL\Eloquent\Model;

/**
 * Class JsonModel.
 */
class JsonModel extends Model
{
    /**
     * @var string
     */
    public $schema = 'schema://dal/models/ext-client';

    /**
     * @var array
     */
    protected $casts = ['company' => 'json'];

    /**
     * @var array
     */
    protected $guarded = [];
}
