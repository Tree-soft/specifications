<?php

namespace TreeSoft\Tests\Specifications\Mocks\Specifications;

use TreeSoft\Specifications\Checkers\Request\RequestChecker;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class EmptyQuerySpecification extends RequestChecker
{
    protected $querySchema = 'schema://common/force-empty';
}
