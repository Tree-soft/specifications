<?php

namespace TreeSoft\Tests\Specifications\Transforming\Transformers;

use TreeSoft\Specifications\Transforming\Transformers\NullTransformer;

/**
 * Class NullResolverTest.
 */
class NullResolverTest extends TestCase
{
    /**
     * @var NullTransformer
     */
    protected $transformer;

    /**
     * @var string
     */
    protected $transformerClass = NullTransformer::class;

    public function testTransform()
    {
        $this->assertNull($this->transformer->transform(5));
    }
}
