<?php

namespace TreeSoft\Tests\Specifications\Mocks\RequestChecker;

use TreeSoft\Specifications\Checkers\Request\RequestChecker;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class RouteTestChecker extends RequestChecker
{
    /**
     * @var string
     */
    protected $routeSchema = 'schema://mock/integer-check';
}
