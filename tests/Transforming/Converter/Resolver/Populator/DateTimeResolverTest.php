<?php

namespace Mildberry\Tests\Specifications\Transforming\Converter\Resolver\Populator;

use Mildberry\Specifications\Transforming\Converter\Resolvers\Populator\DateTimeResolver;
use Mildberry\Tests\Specifications\TestCase;

/**
 * Class DateTimeResolverTest.
 */
class DateTimeResolverTest extends TestCase
{
    /**
     * @var DateTimeResolver
     */
    private $resolver;

    public function testShouldNotResolve()
    {
        $this->assertFalse($this->resolver->isDateTime(''));
    }

    protected function setUp()
    {
        parent::setUp();

        $this->resolver = $this->app->make(DateTimeResolver::class);
    }
}
