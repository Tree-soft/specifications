<?php

namespace Mildberry\Tests\Specifications\Mocks\Specifications;

use Mildberry\Specifications\Checkers\Request\RequestChecker;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class HeaderTeapotSpecification extends RequestChecker
{
    protected $headerSchema = 'schema://mock/teapot';
}
