<?php

namespace Mildberry\Tests\Specifications\Transforming;

use Mildberry\Specifications\Exceptions\ProhibitedTransformationException;
use Mildberry\Specifications\Exceptions\ResolverNotFoundException;
use Mildberry\Specifications\Schema\Loader;
use Mildberry\Specifications\Support\DataPreparator;
use Mildberry\Specifications\Transforming\TransformerFactory;
use Mildberry\Specifications\Transforming\Transformers\ArrayTransformer;
use Mildberry\Specifications\Transforming\Transformers\ComplexSchemaTransformer;
use Mildberry\Specifications\Transforming\Transformers\CopyTransformer;
use Mildberry\Specifications\Transforming\Transformers\JsonSchemaTransformer;
use Mildberry\Specifications\Transforming\Transformers\NullTransformer;
use Mildberry\Specifications\Transforming\Transformers\SimpleTypeTransformer;
use Mildberry\Tests\Specifications\Mocks\LoaderMock;
use Mildberry\Tests\Specifications\TestCase;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class TransformerFactoryTest extends TestCase
{
    /**
     * @var TransformerFactory
     */
    private $factory;

    /**
     * @dataProvider wrongTypesResolver
     *
     * @param string $message
     * @param mixed $from
     * @param mixed $to
     */
    public function testWrong(string $message, $from, $to)
    {
        $this->expectException(ProhibitedTransformationException::class);
        $this->expectExceptionMessage($message);
        $this->factory->create($from, $to);
    }

    /**
     * @return array
     */
    public function wrongTypesResolver()
    {
        $preparator = new DataPreparator();

        return [
            'strings' => [
                "Transformation from type '111' to type '222' is prohibited",
                '111', '222',
            ],
            'objects' => [
                '',
                $preparator->prepare(['type' => 'string']),
                $preparator->prepare(['$ref' => 'schema://entities/company']),
            ],
        ];
    }

    /**
     * @dataProvider schemasProvider
     *
     * @param mixed $expected
     * @param string $from
     * @param string $to
     */
    public function testResolvers($expected, $from, $to)
    {
        $this->assertInstanceOf($expected, $this->factory->create($from, $to));
    }

    /**
     * @return array
     */
    public function schemasProvider(): array
    {
        $preparator = new DataPreparator();

        return [
            'equal' => [
                CopyTransformer::class,
                'schema://entities/client',
                'schema://entities/client',
            ],
            'json-schema' => [
                JsonSchemaTransformer::class,
                'schema://entities/client',
                'schema://entities/derived/client',
            ],
            'simple' => [
                SimpleTypeTransformer::class,
                'integer',
                'string',
            ],
            'complex' => [
                ComplexSchemaTransformer::class,
                $preparator->prepare(['type' => 'integer']),
                'schema://common/id',
            ],
            'as-object' => [
                CopyTransformer::class,
                $preparator->prepare(['type' => 'string']),
                $preparator->prepare(['type' => 'string']),
            ],
            'array' => [
                ArrayTransformer::class,
                $preparator->prepare([
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ]),
                $preparator->prepare([
                    'type' => 'array',
                    'items' => ['type' => 'integer'],
                ]),
            ],
            'null' => [
                NullTransformer::class,
                $preparator->prepare(['type' => 'string']),
                $preparator->prepare(['type' => 'null']),
            ],
        ];
    }

    public function testRemoveResolver()
    {
        $this->expectException(ResolverNotFoundException::class);
        $this->expectExceptionMessage("Resolver 'json' not found");

        $this->factory->removeResolver('json');
        $this->factory->getResolver('json');
    }

    protected function setUp()
    {
        parent::setUp();

        $this->factory = $this->app->make(TransformerFactory::class);

        $this->app->instance(Loader::class, new LoaderMock([
            'transformations' => $this->getFixturePath('transform/transformations.json'),
            'entities/client' => $this->getFixturePath('schema/client.json'),
            'entities/company' => $this->getFixturePath('schema/company.json'),
            'entities/derived/client' => $this->getFixturePath('schema/derived/client.json'),
            'common/id' => $this->getResourcePath('schema/common/id.json'),
            'common/datetime' => $this->getResourcePath('schema/common/datetime.json'),
        ]));
    }
}
