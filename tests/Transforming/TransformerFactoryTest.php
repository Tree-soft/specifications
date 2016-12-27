<?php

namespace Mildberry\Tests\Specifications\Transforming;

use Mildberry\Specifications\Exceptions\ProhibitedTransformationException;
use Mildberry\Specifications\Schema\Loader;
use Mildberry\Specifications\Transforming\Resolvers\CopyResolver;
use Mildberry\Specifications\Transforming\Resolvers\JsonSchemaResolver;
use Mildberry\Specifications\Transforming\TransformerFactory;
use Mildberry\Specifications\Transforming\Transformers\CopyTransformer;
use Mildberry\Specifications\Transforming\Transformers\JsonSchemaTransformer;
use Mildberry\Tests\Specifications\Mocks\LoaderMock;
use Mildberry\Tests\Specifications\TestCase;
use Illuminate\Config\Repository as Config;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class TransformerFactoryTest extends TestCase
{
    /**
     * @var TransformerFactory
     */
    private $factory;

    public function testWrong()
    {
        $this->expectException(ProhibitedTransformationException::class);
        $this->factory->create('111', '222');
    }

    /**
     * @dataProvider resolversProvider
     *
     * @param mixed $expected
     * @param string $from
     * @param string $to
     */
    public function testResolvers($expected, string $from, string $to)
    {
        /**
         * @var Config $config
         */
        $config = $this->app->make(Config::class);

        $config->set('specifications.transform.resolvers', [
            [
                'class' => JsonSchemaResolver::class,
                'schema' => 'transform://transformations',
            ], [
                'class' => CopyResolver::class,
            ],
        ]);

        $this->assertInstanceOf($expected, $this->factory->create($from, $to));
    }

    /**
     * @return array
     */
    public function resolversProvider(): array
    {
        return [
            'equal' => [
                CopyTransformer::class,
                'schema://entity/client',
                'schema://entity/client',
            ],
            'json-schema' => [
                JsonSchemaTransformer::class,
                'schema://entities/client',
                'schema://entities/derived/client',
            ],
        ];
    }

    protected function setUp()
    {
        parent::setUp();

        $this->factory = $this->app->make(TransformerFactory::class);

        $this->app->instance(Loader::class, new LoaderMock([
            'transformations' => $this->getFixturePath('transform/transformations.json'),
        ]));
    }
}
