<?php

namespace Mildberry\Tests\Specifications\Transforming\Converter;

use Mildberry\Specifications\Schema\Loader;
use Mildberry\Specifications\Transforming\Converter\Extractor;
use Mildberry\Tests\Specifications\Fixtures\Entities\Client;
use Mildberry\Tests\Specifications\Mocks\LoaderMock;
use Mildberry\Tests\Specifications\TestCase;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ExtractorTest extends TestCase
{
    /**
     * @var Extractor
     */
    private $extractor;

    public function testExtract()
    {
        $objects = $this->loadEntities('client-extract.yml');

        /**
         * @var Client $entity
         */
        $entity = $objects['client'];

        $company = $entity->getCompany();

        $data = $this->extractor->convert($entity, 'schema://entities/client');

        $this->assertEquals((object) [
            'name' => $entity->getName(),
            'phone' => $entity->getPhone(),
            'company' => (object) [
                'id' => $company->getId(),
                'name' => $company->getName(),
            ],
        ], $data);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->extractor = $this->app->make(Extractor::class);

        $this->extractor
            ->setNamespace('\Mildberry\Tests\Specifications\Fixtures');

        $this->app->instance(Loader::class, new LoaderMock([
            'entities/client' => $this->getFixturePath('schema/client.json'),
            'entities/company' => $this->getFixturePath('schema/company.json'),
        ]));
    }
}
