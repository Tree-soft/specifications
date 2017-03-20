<?php

namespace Mildberry\Tests\Specifications\Transforming\Transformers;

use Mildberry\Specifications\Exceptions\ProhibitedTransformationException;
use Mildberry\Specifications\Schema\Loader;
use Mildberry\Specifications\Transforming\Transformers\ComplexSchemaTransformer;
use Mildberry\Tests\Specifications\Mocks\LoaderMock;

/**
 * Class ComplexSchemaTransformerTest.
 */
class ComplexSchemaTransformerTest extends TestCase
{
    /**
     * @var ComplexSchemaTransformer
     */
    protected $transformer;

    /**
     * @var string
     */
    protected $transformerClass = ComplexSchemaTransformer::class;

    /**
     * @dataProvider valuesProvider
     *
     * @param mixed $expected
     * @param string $fromSchema
     * @param string $toSchema
     * @param mixed $from
     * @param mixed|null $to
     */
    public function testTransform($expected, string $fromSchema, string $toSchema, $from, $to = null)
    {
        $this->transformer
            ->setFromSchema($fromSchema)
            ->setToSchema($toSchema);

        $this->assertEquals(
            $expected, $this->transformer->transform($from, $to)
        );
    }

    /**
     * @return array
     */
    public function valuesProvider()
    {
        return [
            'complexLeft' => [
                1, 'schema://common/id', 'integer', 1,
            ],
            'complexRight' => [
                1, 'integer', 'schema://common/id', 1,
            ],
        ];
    }

    public function testProhibited()
    {
        $this->expectException(ProhibitedTransformationException::class);
        $this->expectExceptionMessage(
            "Transformation 'schema://common/id' to 'schema://entities/company' is prohibited"
        );

        $this->transformer
            ->setToSchema('schema://entities/company')
            ->setFromSchema('schema://common/id');

        $this->transformer->transform('1');
    }

    protected function setUp()
    {
        parent::setUp();

        $this->app->instance(Loader::class, new LoaderMock([
            'entities/company' => $this->getFixturePath('schema/company.json'),
            'common/id' => $this->getResourcePath('schema/common/id.json'),
        ]));
    }
}
