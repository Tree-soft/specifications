<?php

namespace TreeSoft\Tests\Specifications\Transforming\Converter;

use TreeSoft\Specifications\Schema\Loader;
use TreeSoft\Specifications\Support\DataPreparator;
use TreeSoft\Specifications\Transforming\Converter\Extractor;
use TreeSoft\Tests\Specifications\Fixtures\Entities\Client;
use TreeSoft\Tests\Specifications\Mocks\LoaderMock;
use TreeSoft\Tests\Specifications\TestCase;
use Illuminate\Contracts\Config\Repository as Config;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ExtractorTest extends TestCase
{
    /**
     * @var Extractor
     */
    private $extractor;

    /**
     * @dataProvider dataProvider
     *
     * @param mixed $expected
     * @param mixed $entity
     * @param string $schema
     * @param string $namespace
     */
    public function testExtract(
        $expected, $entity, $schema,
        string $namespace = '\TreeSoft\Tests\Specifications\Fixtures'
    ) {
        /**
         * @var Config $config
         */
        $config = $this->app->make(Config::class);

        $config->set('specifications.namespace', $namespace);

        $this->extractor
            ->setNamespace($namespace ?? '\TreeSoft\Tests\Specifications\Fixtures');

        $data = $this->extractor->convert($entity, $schema);

        $this->assertEquals($expected, $data);
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        $objects = $this->loadEntities(['client-extract.yml', 'complex-extract.yml']);
        $preparator = new DataPreparator();

        /**
         * @var Client $client
         */
        $client = $objects['client'];
        $company = $client->getCompany();

        return [
            'null' => [
                null, null, 'schema://types/null',
            ],
            'int' => [
                1, 1, 'schema://types/int',
            ],
            'simple' => [
                $preparator->prepare([
                    'name' => $client->getName(),
                    'phone' => $client->getPhone(),
                    'company' => [
                        'id' => $company->getId(),
                        'name' => $company->getName(),
                    ],
                ]), $client, 'schema://entities/client',
            ],
            'complexSimple' => [
                (object) [
                    'id' => 1,
                ], $objects['complexSimple'], 'schema://entities/complexType',
                '\TreeSoft\Tests\Specifications\Mocks',
            ],
            'array' => [
                [1, 2, 3], [1, 2, 3], $preparator->prepare([
                    'type' => 'array',
                    'items' => ['type' => 'integer'],
                ]),
            ],
            'null-type' => [
                null, [1, 2, 3], $preparator->prepare(['type' => 'null']),
            ],
        ];
    }

    protected function setUp()
    {
        parent::setUp();

        $this->extractor = $this->app->make(Extractor::class);

        $this->app->instance(Loader::class, new LoaderMock([
            'entities/client' => $this->getFixturePath('schema/client.json'),
            'entities/company' => $this->getFixturePath('schema/company.json'),
            'entities/nullable' => $this->getFixturePath('schema/nullable.json'),
            'entities/complexType' => $this->getFixturePath('schema/complexType.json'),
            'entities/objectMock' => $this->getFixturePath('schema/objectMock.json'),
            'common/id' => $this->getResourcePath('schema/common/id.json'),
            'types/int' => $this->getFixturePath('schema/types/int.json'),
            'types/null' => $this->getFixturePath('schema/types/null.json'),
        ]));
    }
}
