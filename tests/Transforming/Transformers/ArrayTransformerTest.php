<?php

namespace TreeSoft\Tests\Specifications\Transforming\Transformers;

use TreeSoft\Specifications\Support\DataPreparator;
use TreeSoft\Specifications\Transforming\Transformers\ArrayTransformer;

/**
 * Class ArrayTransformerTest.
 */
class ArrayTransformerTest extends TestCase
{
    /**
     * @var ArrayTransformer
     */
    protected $transformer;

    /**
     * @var string
     */
    protected $transformerClass = ArrayTransformer::class;

    /**
     * @dataProvider valuesProvider
     *
     * @param mixed $expected
     * @param string|object|mixed $fromSchema
     * @param string $toSchema
     * @param mixed $from
     * @param mixed|null $to
     */
    public function testTransform($expected, $fromSchema, $toSchema, $from, $to = null)
    {
        $this->transformer
            ->setFromSchema($fromSchema)
            ->setToSchema($toSchema);

        $this->assertEquals($expected, $this->transformer->transform($from, $to));
    }

    /**
     * @return array
     */
    public function valuesProvider()
    {
        $preparator = new DataPreparator();

        return [
            'common' => [
                [1, 2, 3],
                $preparator->prepare(['type' => 'string']),
                $preparator->prepare(['type' => 'integer']),
                ['1', '2', '3'],
            ],
            'leftEmpty' => [
                [1, 2, 3],
                null,
                $preparator->prepare(['type' => 'integer']),
                [1, 2, 3],
            ],
            'rightEmpty' => [
                [1, 2, 3],
                $preparator->prepare(['type' => 'integer']),
                null,
                [1, 2, 3],
            ],
        ];
    }
}
