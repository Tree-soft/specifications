<?php

namespace Mildberry\Tests\Specifications\Http\Transformers;

use Mildberry\Specifications\Http\Requests\Request;
use Mildberry\Specifications\Http\Transformers\EntityTransformer;
use Mildberry\Specifications\Schema\Loader;
use Mildberry\Specifications\Transforming\Resolvers\JsonSchemaResolver;
use Mildberry\Tests\Specifications\Fixtures\Entities\Client;
use Mildberry\Tests\Specifications\Http\TestCase;
use Illuminate\Config\Repository as Config;
use Mildberry\Tests\Specifications\Mocks\LoaderMock;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class EntityTransformerTest extends TestCase
{
    /**
     * @var EntityTransformer
     */
    private $transformer;

    /**
     * @dataProvider classesProvider
     *
     * @param string $expected
     * @param string $class
     * @param string|null $namespace
     */
    public function testGetSchema(string $expected, string $class, $namespace = '\\')
    {
        /**
         * @var Config $config
         */
        $config = $this->app->make(Config::class);

        $config->set('specifications.namespace', $namespace);

        $this->transformer->setNamespace($namespace);

        $this->assertEquals($expected, $this->transformer->getSchema($class));
    }

    /**
     * @return array
     */
    public function classesProvider()
    {
        return [
            'root' => ['schema://entities/client', '\Entities\Client'],
            'namespace' => ['schema://entities/client', '\Core\Entities\Client', '\Core'],
            'client' => ['schema://entities/client', Client::class, '\Mildberry\Tests\Specifications\Fixtures'],
        ];
    }

    public function testPopulateFromRequest()
    {
        /**
         * @var Config $config
         */
        $config = $this->app->make(Config::class);

        $namespace = '\Mildberry\Tests\Specifications\Fixtures';

        $config->set('specifications.namespace', $namespace);

        $this->transformer
            ->setNamespace($namespace)
            ->setClass(Client::class);

        $request = new class() extends Request {
            /**
             * @var string
             */
            protected $dataSchema = 'schema://entities/simple-client';

            /**
             * @var mixed
             */
            public $data;

            /**
             * @return mixed
             */
            public function getData()
            {
                return $this->prepareData($this->data);
            }
        };

        $data = [
            'name' => 'Client name',
            'phone' => '+71234567890',
        ];

        $request->data = $data;

        /**
         * @var Client $client
         */
        $client = $this->transformer->populateFromRequest($request);

        $this->assertInstanceOf(Client::class, $client);
    }

    public function testExtractToResponse()
    {
        $objects = $this->loadEntities('client-extract.yml');

        $config = $this->app->make(Config::class);

        $namespace = '\Mildberry\Tests\Specifications\Fixtures';
        $config->set('specifications.namespace', $namespace);

        $this->transformer
            ->setNamespace($namespace)
            ->setClass(Client::class);

        /**
         * @var Client $client
         */
        $client = $objects['client'];

        $data = $this->transformer->extractToResponse($client, 'schema://entities/simple-client');

        $this->assertEquals((object) [
            'name' => $client->getName(),
            'phone' => $client->getPhone(),
        ], $data);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->transformer = $this->app->make(EntityTransformer::class);

        /**
         * @var JsonSchemaResolver $jsonResolver
         */
        $jsonResolver = $this->transformer->getFactory()->getResolver('json');

        $resolverConfig = $jsonResolver->getConfig();

        $resolverConfig['schema'] = 'transform://transformations-request';

        $jsonResolver->setConfig($resolverConfig);

        $this->app->instance(Loader::class, new LoaderMock([
            'entities/client' => $this->getFixturePath('schema/client.json'),
            'entities/simple-client' => $this->getFixturePath('schema/simple-client.json'),
            'entities/company' => $this->getFixturePath('schema/company.json'),
        ]));
    }
}
