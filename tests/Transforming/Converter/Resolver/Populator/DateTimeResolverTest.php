<?php

namespace Mildberry\Tests\Specifications\Transforming\Converter\Resolver\Populator;

use Mildberry\Specifications\Support\DataPreparator;
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

        $preparator = new DataPreparator();

        $this->resolver = $this->app->make(DateTimeResolver::class);
        $this->resolver
            ->setSchema($preparator->prepare([
                'id' => 'schema://common/datetime',
            ]));
    }
}
