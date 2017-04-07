<?php

namespace TreeSoft\Tests\Specifications\Transforming\Converter;

use TreeSoft\Specifications\Schema\Loader;
use TreeSoft\Specifications\Support\DataPreparator;
use TreeSoft\Specifications\Transforming\Converter\Populator;
use TreeSoft\Tests\Specifications\Fixtures\Entities\Client;
use TreeSoft\Tests\Specifications\Fixtures\Entities\Company;
use TreeSoft\Tests\Specifications\Mocks\LoaderMock;
use TreeSoft\Tests\Specifications\TestCase;
use Illuminate\Contracts\Config\Repository as Config;
use DateTime;

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

    public function testDateTime()
    {
        $expected = '28.02.2017';

        /**
         * @var DateTime $actual
         */
        $actual = $this->populator->convert($expected, 'schema://common/datetime');

        $this->assertEquals($expected, $actual->format('d.m.Y'));
    }

    protected function setUp()
    {
        parent::setUp();

        /**
         * @var Config $config
         */
        $config = $this->app->make(Config::class);

        $config->set('specifications.namespace', '\TreeSoft\Tests\Specifications\Fixtures');

        $this->populator = $this->app->make(Populator::class);

        $this->app->instance(Loader::class, new LoaderMock([
            'entities/client' => $this->getFixturePath('schema/client.json'),
            'entities/company' => $this->getFixturePath('schema/company.json'),
            'common/datetime' => $this->getResourcePath('schema/common/datetime.json'),
        ]));
    }
}
