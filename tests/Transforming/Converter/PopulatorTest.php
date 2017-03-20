<?php

namespace Mildberry\Tests\Specifications\Transforming\Converter;

use Mildberry\Specifications\Schema\Loader;
use Mildberry\Specifications\Support\DataPreparator;
use Mildberry\Specifications\Transforming\Converter\Populator;
use Mildberry\Tests\Specifications\Fixtures\Entities\Client;
use Mildberry\Tests\Specifications\Fixtures\Entities\Company;
use Mildberry\Tests\Specifications\Mocks\LoaderMock;
use Mildberry\Tests\Specifications\TestCase;
use Illuminate\Contracts\Config\Repository as Config;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class PopulatorTest extends TestCase
{
    /**
     * @var Populator
     */
    private $populator;

    public function testPopulateSimple()
    {
        $preparator = new DataPreparator();

        $data = [
            'name' => 'Client name',
            'phone' => '+71234567890',
            'company' => [
                'id' => 1,
                'name' => 'Company name',
            ],
        ];

        /**
         * @var Client $actual
         */
        $actual = $this->populator->convert($preparator->prepare($data), 'schema://entities/client');

        $this->assertInstanceOf(Client::class, $actual);

        $actualCompany = $actual->getCompany();

        $this->assertInstanceOf(Company::class, $actualCompany);

        $this->assertEquals($data, [
            'name' => $actual->getName(),
            'phone' => $actual->getPhone(),
            'company' => [
                'id' => $actualCompany->getId(),
                'name' => $actualCompany->getName(),
            ],
        ]);
    }

    public function testIncomplete()
    {
        $preparator = new DataPreparator();

        $data = [
            'phone' => '+71234567890',
        ];

        /**
         * @var Client $actual
         */
        $actual = $this->populator->convert($preparator->prepare($data), 'schema://entities/client');

        $this->assertInstanceOf(Client::class, $actual);

        $this->assertEmpty($actual->getCompany());
        $this->assertEmpty($actual->getName());

        $this->assertEquals($data, [
            'phone' => $actual->getPhone(),
        ]);
    }

    protected function setUp()
    {
        parent::setUp();

        /**
         * @var Config $config
         */
        $config = $this->app->make(Config::class);

        $config->set('specifications.namespace', '\Mildberry\Tests\Specifications\Fixtures');

        $this->populator = $this->app->make(Populator::class);

        $this->app->instance(Loader::class, new LoaderMock([
            'entities/client' => $this->getFixturePath('schema/client.json'),
            'entities/company' => $this->getFixturePath('schema/company.json'),
        ]));
    }
}
