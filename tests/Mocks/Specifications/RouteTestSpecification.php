<?php

namespace Mildberry\Tests\Specifications\Mocks\Specifications;

use Mildberry\Specifications\Checkers\Request\RequestChecker;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class RouteTestSpecification extends RequestChecker
{
    protected $routeSchema = 'schema://mock/integer-check';
}
