<?php

namespace Mildberry\Tests\Specifications\Mocks\Specifications;

use Mildberry\Specifications\Checkers\Request\RequestChecker;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class EmptyQuerySpecification extends RequestChecker
{
    protected $querySchema = 'schema://common/force-empty';
}
