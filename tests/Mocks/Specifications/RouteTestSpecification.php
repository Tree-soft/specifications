<?php

namespace TreeSoft\Tests\Specifications\Mocks\Specifications;

use TreeSoft\Specifications\Checkers\Request\RequestChecker;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class RouteTestSpecification extends RequestChecker
{
    protected $routeSchema = 'schema://mock/integer-check';
}
