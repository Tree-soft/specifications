<?php

namespace TreeSoft\Tests\Specifications\Transforming\Transformers\JsonSchema\Transformations;

use TreeSoft\Specifications\Transforming\Transformers\ValueDescriptor;
use TreeSoft\Tests\Specifications\TestCase as ParentTestCase;
use TreeSoft\Specifications\Transforming\Transformers\JsonSchema\Transformations\AbstractTransformation;

class TestCase extends ParentTestCase
{
    /**
     * @var AbstractTransformation
     */
    protected $transformation;

    /**
     * @var string
     */
    protected $transformationClass;

    /**
     * @param mixed $expected
     * @param mixed $from
     * @param mixed $to
     */
    public function assertTransformerResult(ValueDescriptor $expected, ValueDescriptor $from, ValueDescriptor $to)
    {
        $handled = false;

        $this->assertEquals(
            $expected,
            $this->transformation->apply(
                $from, $to, function ($from, $value) use (&$handled, $to) {
                    $this->assertEquals($value, $to);
                    $handled = true;

                    return $from;
                }
            )
        );

        $this->assertTrue($handled);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->transformation = $this->app->make($this->transformationClass);
    }
}
