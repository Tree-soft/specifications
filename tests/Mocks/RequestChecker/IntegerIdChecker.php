<?php

namespace TreeSoft\Tests\Specifications\Mocks\RequestChecker;

use TreeSoft\Specifications\Checkers\Request\RequestChecker;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class IntegerIdChecker extends RequestChecker
{
    /**
     * @var string
     */
    protected $dataSchema = 'schema://mock/integer-check';
}
