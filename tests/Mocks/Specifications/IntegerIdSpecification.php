<?php

namespace Mildberry\Tests\Specifications\Mocks\Specifications;

use Mildberry\Specifications\Checkers\Request\RequestChecker;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class IntegerIdSpecification extends RequestChecker
{
    protected $dataSchema = 'schema://mock/integer-check';
}
