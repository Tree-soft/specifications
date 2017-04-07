<?php

namespace TreeSoft\Tests\Specifications\Transforming\Transformers;

use TreeSoft\Specifications\Exceptions\ProhibitedTransformationException;
use TreeSoft\Specifications\Schema\Loader;
use TreeSoft\Specifications\Support\DataPreparator;
use TreeSoft\Specifications\Transforming\Transformers\ComplexSchemaTransformer;
use TreeSoft\Tests\Specifications\Mocks\LoaderMock;

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

        $this->assertEquals(
            $expected, $this->transformer->transform($from, $to)
        );
    }

    /**
     * @return array
     */
    public function valuesProvider()
    {
        $preparator = new DataPreparator();

        return [
            'complexLeft' => [
                1, $preparator->prepare(['$ref' => 'schema://common/id']),
                $preparator->prepare(['type' => 'integer']), 1,
            ],
            'complexRight' => [
                1, 'integer', 'schema://common/id', 1,
            ],
        ];
    }

    /**
     * @dataProvider wrongSchemaProvider
     *
     * @param string $message
     * @param mixed $from
     * @param mixed $to
     */
    public function testProhibited(string $message, $from, $to)
    {
        $this->expectException(ProhibitedTransformationException::class);
        $this->expectExceptionMessage($message);

        $this->transformer
            ->setFromSchema($from)
            ->setToSchema($to);

        $this->transformer->transform('1');
    }

    /**
     * @return array
     */
    public function wrongSchemaProvider()
    {
        $preparator = new DataPreparator();

        return [
            'strings' => [
                "Transformation from 'schema://entities/company' to 'schema://types/int-string' is prohibited",
                'schema://entities/company', 'schema://types/int-string',
            ],
            'objects' => [
                "Transformation from 'schema://entities/company' to 'schema://types/int-string' is prohibited",
                $preparator->prepare(['$ref' => 'schema://entities/company']),
                $preparator->prepare(['$ref' => 'schema://types/int-string']),
            ],
        ];
    }

    protected function setUp()
    {
        parent::setUp();

        $this->app->instance(Loader::class, new LoaderMock([
            'entities/company' => $this->getFixturePath('schema/company.json'),
            'types/int-string' => $this->getFixturePath('schema/types/int-string.json'),
            'common/id' => $this->getResourcePath('schema/common/id.json'),
        ]));
    }
}
