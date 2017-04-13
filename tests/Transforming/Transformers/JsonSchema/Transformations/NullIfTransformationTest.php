<?php

namespace TreeSoft\Tests\Specifications\Transforming\Transformers\JsonSchema\Transformations;

use TreeSoft\Specifications\Transforming\Transformers\JsonSchema\Transformations\NullIfTransformation;
use TreeSoft\Specifications\Transforming\Transformers\ValueDescriptor;

/**
 * Class NullIfTransformationTest.
 */
class NullIfTransformationTest extends TestCase
{
    /**
     * @var string
     */
    protected $transformationClass = NullIfTransformation::class;

    public function testApply()
    {
        $from = new ValueDescriptor();

        $from
            ->setValue(1);

        $to = new ValueDescriptor();

        $this->assertTransformerResult($from, $from, $to);
    }

    public function testSkip()
    {
        $value = new ValueDescriptor();

        $this->transformation->apply(
            $value, $value, function () {
                TestCase::fail('That should not be called.');
            }
        );
    }
}
