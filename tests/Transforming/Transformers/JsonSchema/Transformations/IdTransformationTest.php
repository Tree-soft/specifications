<?php

namespace Mildberry\Tests\Specifications\Transforming\Transformers\JsonSchema\Transformations;

use Mildberry\Specifications\Schema\LaravelFactory;
use Mildberry\Specifications\Schema\Loader;
use Mildberry\Specifications\Support\DataPreparator;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Transformations\IdTransformation;
use Mildberry\Specifications\Transforming\Transformers\ValueDescriptor;
use Mildberry\Tests\Specifications\Mocks\LoaderMock;
use Mildberry\Tests\Specifications\TestCase;

/**
 * Class IdTransformationTest.
 */
class IdTransformationTest extends TestCase
{
    /**
     * @var IdTransformation
     */
    private $transformations;

    /**
     * @dataProvider valuesProvider
     *
     * @param ValueDescriptor $expected
     * @param ValueDescriptor $from
     * @param ValueDescriptor $to
     * @param array $configure
     */
    public function testApply(ValueDescriptor $expected, ValueDescriptor $from, ValueDescriptor $to, array $configure)
    {
        $this->transformations
            ->configure($configure);

        /**
         * @var LaravelFactory $factory
         */
        $factory = $this->app->make(LaravelFactory::class);

        foreach ([$expected, $from, $to] as $item) {
            $item
                ->setSchema($factory->schema($item->getSchema()));
        }

        $this->assertEquals(
            $expected,
            $this->transformations->apply($from, $to, function ($from) {
                return $from;
            })
        );
    }

    /**
     * @return array
     */
    public function valuesProvider()
    {
        $preparator = new DataPreparator();
        $objects = $this->loadEntities('id-transformation.yml');

        /**
         * @var ValueDescriptor $value
         */
        foreach ($objects as $value) {
            $value
                ->setValue($preparator->prepare($value->getValue()))
                ->setSchema($preparator->prepare($value->getSchema()));
        }

        return [
            'simple' => [
                $objects['expected'],
                $objects['from'],
                $objects['to'],
                [],
            ],
            'other-field' => [
                $objects['expected_2'],
                $objects['from'],
                $objects['to'],
                ['name'],
            ],
        ];
    }

    protected function setUp()
    {
        parent::setUp();

        $this->transformations = $this->app->make(IdTransformation::class);

        $this->app->instance(Loader::class, new LoaderMock([
            'entities/company' => $this->getFixturePath('schema/company.json'),
        ]));
    }
}
