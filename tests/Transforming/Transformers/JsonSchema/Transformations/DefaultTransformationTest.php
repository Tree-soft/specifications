<?php

namespace Mildberry\Tests\Specifications\Transforming\Transformers\JsonSchema\Transformations;

use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Transformations\DefaultTransformation;
use Mildberry\Specifications\Transforming\Transformers\ValueDescriptor;
use Mildberry\Tests\Specifications\TestCase;

/**
 * Class DefaultTransformationTest.
 */
class DefaultTransformationTest extends TestCase
{
    /**
     * @var DefaultTransformation
     */
    private $transformation;

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

    protected function setUp()
    {
        parent::setUp();

        $this->transformation = $this->app->make(DefaultTransformation::class);
    }
}
