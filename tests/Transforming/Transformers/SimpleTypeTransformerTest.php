<?php

namespace TreeSoft\Tests\Specifications\Transforming\Transformers;

use TreeSoft\Specifications\Transforming\Transformers\SimpleType\Casters\BooleanCaster;
use TreeSoft\Specifications\Transforming\Transformers\SimpleType\Casters\FloatCaster;
use TreeSoft\Specifications\Transforming\Transformers\SimpleType\Casters\IntegerCaster;
use TreeSoft\Specifications\Transforming\Transformers\SimpleType\Casters\StringCaster;
use TreeSoft\Specifications\Transforming\Transformers\SimpleTypeTransformer;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class SimpleTypeTransformerTest extends TestCase
{
    /**
     * @var SimpleTypeTransformer
     */
    protected $transformer;

    /**
     * @var string
     */
    protected $transformerClass = SimpleTypeTransformer::class;

    /**
     * @dataProvider casterProvider
     *
     * @param string $fromType
     * @param string $toType
     * @param mixed $expected
     * @param mixed $value
     */
    public function testCast(string $fromType, string $toType, $expected, $value)
    {
        $this->transformer
            ->setFromType($fromType)
            ->setToType($toType)
            ->setCasters([
                'boolean' => BooleanCaster::class,
                'number' => FloatCaster::class,
                'string' => StringCaster::class,
                'integer' => IntegerCaster::class,
            ]);

        $this->assertSame($expected, $this->transformer->transform($value));
    }

    /**
     * @return array
     */
    public function casterProvider()
    {
        return [
            'boolean' => [
                'integer', 'boolean', true, 5,
            ],
            'float' => [
                'integer', 'number', 5.0, 5,
            ],
            'integer' => [
                'string', 'integer', 5, '5',
            ],
            'string' => [
                'integer', 'string', '5', 5,
            ],
        ];
    }
}
