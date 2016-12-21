<?php

namespace Mildberry\Tests\Specifications\Mocks\Specifications;

use Mildberry\Specifications\Specifications\Request\RequestSpecification;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class RouteTestSpecification extends RequestSpecification
{
    protected $routeSchema = 'schema://mock/integer-check';
}
