<?php

namespace TreeSoft\Tests\Specifications\Mocks\RequestChecker;

use TreeSoft\Specifications\Checkers\Request\RequestChecker;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class EmptyQueryChecker extends RequestChecker
{
    /**
     * @var string
     */
    protected $querySchema = 'schema://common/force-empty';
}
