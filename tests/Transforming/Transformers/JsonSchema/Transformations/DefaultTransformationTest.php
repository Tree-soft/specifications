<?php

namespace TreeSoft\Tests\Specifications\Transforming\Transformers\JsonSchema\Transformations;

use TreeSoft\Specifications\Transforming\Transformers\JsonSchema\Transformations\DefaultTransformation;
use TreeSoft\Specifications\Transforming\Transformers\ValueDescriptor;

/**
 * Class DefaultTransformationTest.
 */
class DefaultTransformationTest extends TestCase
{
    /**
     * @var string
     */
    protected $transformationClass = DefaultTransformation::class;

    /**
     * @dataProvider defaultsProvider
     *
     * @param ValueDescriptor $expected
     * @param ValueDescriptor$from
     * @param ValueDescriptor $to
     * @param mixed $value
     * @param mixed|object $schema
     */
    public function testApply(ValueDescriptor $expected, ValueDescriptor $from, ValueDescriptor $to, $value, $schema = null)
    {
        $this->transformation->configure([
            $value, $schema,
        ]);

        $this->assertTransformerResult($expected, $from, $to);
    }

    /**
     * @return array
     */
    public function defaultsProvider(): array
    {
        $schemaArray = (object) ['type' => 'array'];

        return [
            'default' => [
                (new ValueDescriptor())
                    ->setValue([])
                    ->setSchema($schemaArray),
                (new ValueDescriptor())
                    ->setValue(null)
                    ->setSchema((object) ['type' => 'null']),
                (new ValueDescriptor())
                    ->setValue(null)
                    ->setSchema($schemaArray),
                [],
            ],
        ];
    }
}
