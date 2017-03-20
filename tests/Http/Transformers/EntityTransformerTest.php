<?php

namespace Mildberry\Tests\Specifications\Http\Transformers;

use Mildberry\Specifications\Http\Requests\Request;
use Mildberry\Specifications\Http\Transformers\EntityTransformer;
use Mildberry\Specifications\Schema\Loader;
use Mildberry\Specifications\Transforming\Resolvers\CopyResolver;
use Mildberry\Specifications\Transforming\Resolvers\JsonSchemaResolver;
use Mildberry\Specifications\Transforming\Resolvers\SimpleTypeResolver;
use Mildberry\Specifications\Transforming\Transformers\SimpleType\Casters\StringCaster;
use Mildberry\Tests\Specifications\Fixtures\Entities\Client;
use Mildberry\Tests\Specifications\Fixtures\Entities\Company;
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
    public function testGetSchema(string $expected, string $class, $namespace = null)
    {
        if (isset($namespace)) {
            $this->transformer->setNamespace($namespace);
        }

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

        $config->set('specifications.transform.resolvers.json-schema.schema', 'transform://transformations-request');

        $this->transformer
            ->setNamespace('\Mildberry\Tests\Specifications\Fixtures')
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
        /**
         * @var Config $config
         */
        $config = $this->app->make(Config::class);

        $config->set('specifications.transform.resolvers', [
            [
                'class' => JsonSchemaResolver::class,
                'schema' => 'transform://transformations-request',
            ], [
                'class' => CopyResolver::class,
            ], [
                'class' => SimpleTypeResolver::class,
                'casters' => [
                    'string' => StringCaster::class,
                ],
            ],
        ]);

        $this->transformer
            ->setNamespace('\Mildberry\Tests\Specifications\Fixtures')
            ->setClass(Client::class);

        $entity = new Client();

        $company = new Company();

        $company
            ->setId(1)
            ->setName('Company name');

        $entity
            ->setName('Company name')
            ->setPhone('+71234567890')
            ->setCompany($company);

        $data = $this->transformer->extractToResponse($entity, 'schema://entities/simple-client');

        $this->assertEquals((object) [
            'name' => $entity->getName(),
            'phone' => $entity->getPhone(),
        ], $data);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->transformer = $this->app->make(EntityTransformer::class);

        $this->app->instance(Loader::class, new LoaderMock([
            'entities/client' => $this->getFixturePath('schema/client.json'),
            'entities/simple-client' => $this->getFixturePath('schema/simple-client.json'),
            'entities/company' => $this->getFixturePath('schema/company.json'),
        ]));
    }
}
