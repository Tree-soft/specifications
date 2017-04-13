<?php

namespace TreeSoft\Tests\Specifications\Transforming\Transformers\JsonSchema\Transformations;

use TreeSoft\Specifications\Transforming\Transformers\JsonSchema\Transformations\NormalizeTransformation;
use TreeSoft\Specifications\Transforming\Transformers\ValueDescriptor;

/**
 * Class NormalizeTransformationTest.
 */
class NormalizeTransformationTest extends TestCase
{
    /**
     * @var string
     */
    protected $transformationClass = NormalizeTransformation::class;

    /**
     * @dataProvider defaultsProvider
     *
     * @param ValueDescriptor $expected
     * @param ValueDescriptor$from
     * @param ValueDescriptor $to
     */
    public function testApply(ValueDescriptor $expected, ValueDescriptor $from, ValueDescriptor $to)
    {
        $this->assertTransformerResult($expected, $from, $to);
    }

    /**
     * @return array
     */
    public function defaultsProvider()
    {
        $arr = [
            3 => 'val 1',
            5 => 'val 2',
            7 => 'val 7',
        ];

        return [
            'default' => [
                (new ValueDescriptor())
                    ->setValue(array_values($arr))
                    ->setSchema(['type' => 'array']),
                (new ValueDescriptor())
                    ->setValue($arr)
                    ->setSchema(['type' => 'array']),
                (new ValueDescriptor())
                    ->setSchema(['type' => 'array']),
            ],
        ];
    }
}
