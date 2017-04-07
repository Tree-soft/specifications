<?php

namespace TreeSoft\Tests\Specifications\Mocks\Specifications;

use TreeSoft\Specifications\Checkers\Request\RequestChecker;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class HeaderTeapotSpecification extends RequestChecker
{
    protected $headerSchema = 'schema://mock/teapot';
}
