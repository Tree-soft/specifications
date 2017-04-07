<?php

namespace TreeSoft\Tests\Specifications\Fixtures\Requests;

use TreeSoft\Specifications\Http\Requests\Request;

/**
 * @author Json-schema request generator
 */
class ExampleRequest extends Request
{
    /**
     * @var string
     */
    protected $dataSchema = 'schema://example/requests/client';
}
