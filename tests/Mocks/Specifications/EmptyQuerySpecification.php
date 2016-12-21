<?php

namespace Mildberry\Tests\Specifications\Mocks\Specifications;

use Mildberry\Specifications\Specifications\Request\RequestSpecification;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class EmptyQuerySpecification extends RequestSpecification
{
    protected $querySchema = 'schema://common/force-empty';
}
