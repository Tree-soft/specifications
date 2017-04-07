<?php

namespace TreeSoft\Tests\Specifications\Transforming\Converter\Resolver\Populator;

use TreeSoft\Specifications\Support\DataPreparator;
use TreeSoft\Specifications\Transforming\Converter\Resolvers\Populator\DateTimeResolver;
use TreeSoft\Tests\Specifications\TestCase;

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
