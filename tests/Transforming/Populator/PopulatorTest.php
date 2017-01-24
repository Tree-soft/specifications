<?php

namespace Mildberry\Tests\Specifications\Transforming\Populator;

use Mildberry\Specifications\Schema\Loader;
use Mildberry\Specifications\Support\DataPreparator;
use Mildberry\Specifications\Transforming\Populator\Populator;
use Mildberry\Tests\Specifications\Fixtures\Entities\Client;
use Mildberry\Tests\Specifications\Fixtures\Entities\Company;
use Mildberry\Tests\Specifications\Mocks\LoaderMock;
use Mildberry\Tests\Specifications\TestCase;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class PopulatorTest extends TestCase
{
    /**
     * @var Populator
     */
    private $populator;

    public function testPopulate()
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
        $actual = $this->populator->populate($preparator->prepare($data), 'schema://entities/client');

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
            'name' => 'Client name',
            'phone' => '+71234567890',
        ];

        /**
         * @var Client $actual
         */
        $actual = $this->populator->populate($preparator->prepare($data), 'schema://entities/client');

        $this->assertInstanceOf(Client::class, $actual);

        $this->assertEmpty($actual->getCompany());

        $this->assertEquals($data, [
            'name' => $actual->getName(),
            'phone' => $actual->getPhone(),
        ]);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->populator = $this->app->make(Populator::class);

        $this->populator
            ->setNamespace('\Mildberry\Tests\Specifications\Fixtures');

        $this->app->instance(Loader::class, new LoaderMock([
            'entities/client' => $this->getFixturePath('schema/client.json'),
            'entities/company' => $this->getFixturePath('schema/company.json'),
        ]));
    }
}
