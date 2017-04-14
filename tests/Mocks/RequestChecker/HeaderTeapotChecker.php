<?php

namespace TreeSoft\Tests\Specifications\Mocks\RequestChecker;

use TreeSoft\Specifications\Checkers\Request\RequestChecker;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class HeaderTeapotChecker extends RequestChecker
{
    /**
     * @var string
     */
    protected $headerSchema = 'schema://mock/teapot';
}
