<?php

namespace Mildberry\Tests\Specifications\Fixtures\Requests;

use Mildberry\Specifications\Http\Requests\Request as ParentRequest;

/**
 * @author Json-schema request generator
 */
class Request extends ParentRequest
{
    /**
     * @var string
     */
    protected $headerSchema = 'schema://example/requests/client2';

    /**
     * @var string
     */
    protected $dataSchema = 'schema://example/requests/client';
}
