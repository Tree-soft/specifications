<?php

namespace Mildberry\Tests\Specifications\Fixtures\Requests;

use Mildberry\Specifications\Http\Requests\Request;

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
