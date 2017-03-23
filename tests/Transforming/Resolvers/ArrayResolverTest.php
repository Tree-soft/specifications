<?php

namespace Mildberry\Tests\Specifications\Transforming\Resolvers;

use Mildberry\Specifications\Support\DataPreparator;
use Mildberry\Specifications\Transforming\Resolvers\ArrayResolver;
use Mildberry\Tests\Specifications\TestCase;

/**
 * Class ArrayResolverTest.
 */
class ArrayResolverTest extends TestCase
{
    /**
     * @var ArrayResolver
     */
    private $resolver;

    public function testCreate()
    {
        $preparator = new DataPreparator();

        $from = $preparator->prepare([
            'type' => 'array',
            'items' => ['type' => 'string'],
        ]);

        $to = $preparator->prepare([
            'type' => 'array',
            'items' => ['type' => 'integer'],
        ]);

        $transformer = $this->resolver->createTransformer($from, $to);

        $this->assertEquals([
            'from' => $from->items,
            'to' => $to->items,
        ], [
            'from' => $transformer->getFromSchema(),
            'to' => $transformer->getToSchema(),
        ]);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->resolver = $this->app->make(ArrayResolver::class);
    }
}
