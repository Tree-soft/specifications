<?php

namespace Mildberry\Tests\Specifications\Transforming\Converter\Resolver\Populator;

use Mildberry\Specifications\Support\DataPreparator;
use Mildberry\Specifications\Transforming\Converter\Resolvers\Populator\ObjectResolver;
use Mildberry\Tests\Specifications\TestCase;

/**
 * Class ObjectResolverTest.
 */
class ObjectResolverTest extends TestCase
{
    /**
     * @var ObjectResolver
     */
    private $resolver;

    public function testShouldNotResolve()
    {
        $preparator = new DataPreparator();

        $this->resolver
            ->setSchema(
                $preparator->prepare([
                    'oneOf' => [
                        ['type' => 'object'],
                        ['type' => 'null'],
                    ],
                ])
            );

        $this->assertFalse($this->resolver->isObject((object) ['test' => 1234]));
    }

    protected function setUp()
    {
        parent::setUp();

        $this->resolver = $this->app->make(ObjectResolver::class);
    }
}
