<?php

namespace Mildberry\Tests\Specifications\Mocks\Specifications;

use Mildberry\Specifications\Specifications\Request\RequestSpecification;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class HeaderTeapotSpecification extends RequestSpecification
{
    protected $headerSchema = 'schema://mock/teapot';
}
