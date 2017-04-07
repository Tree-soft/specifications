<?php

namespace TreeSoft\Tests\Specifications\Transforming\Converter\Resolver;

use TreeSoft\Specifications\Support\DataPreparator;
use TreeSoft\Specifications\Transforming\Converter\Resolvers\SimpleResolver;
use TreeSoft\Tests\Specifications\TestCase;

/**
 * Class SimpleResolverTest.
 */
class SimpleResolverTest extends TestCase
{
    /**
     * @var SimpleResolver
     */
    private $resolver;

    public function testShouldNotResolve()
    {
        $preparator = new DataPreparator();

        $this->resolver
            ->setSchema($preparator->prepare([
                'id' => 'schema://common/datetime',
                'type' => 'string',
            ]));

        $this->assertFalse($this->resolver->isSimpleType());
    }

    protected function setUp()
    {
        parent::setUp();

        $this->resolver = $this->app->make(SimpleResolver::class);
    }
}
