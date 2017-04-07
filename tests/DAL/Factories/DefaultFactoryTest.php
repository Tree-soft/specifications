<?php

namespace TreeSoft\Tests\Specifications\DAL\Factories;

use TreeSoft\Specifications\DAL\Exceptions\RepositoryNotFoundException;
use TreeSoft\Specifications\DAL\Factories\DefaultFactory;
use TreeSoft\Tests\Specifications\DAL\TestCase;
use Illuminate\Contracts\Config\Repository as Config;
use TreeSoft\Tests\Specifications\Fixtures\Entities\Client;
use TreeSoft\Tests\Specifications\Mocks\Repositories\ClientRepository;


/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class DefaultFactoryTest extends TestCase
{
    /**
     * @var DefaultFactory
     */
    private $factory;

    /**
     * @dataProvider implementationProvider
     * @param string $class
     * @param string $expected
     * @param array $implementation
     */
    public function testCreate(string $class, string $expected, array $implementation) {
        /**
         * @var Config $config
         */
        $config = $this->app->make(Config::class);

        $config->set('dal.repositories', $implementation);

        $actual = $this->factory->create($class);

        $this->assertEquals($expected, get_class($actual));
    }

    public function testUnknownClass() {
        $class = Client::class;

        $this->expectException(RepositoryNotFoundException::class);
        $this->expectExceptionMessage("Repository for class '{$class}' not found");

        $this->factory->create($class);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->factory = $this->app->make(DefaultFactory::class);
    }

    /**
     * @return array
     */
    public function implementationProvider() {
        return [
            'simple' => [
                Client::class, ClientRepository::class, [
                    Client::class => ClientRepository::class
                ]
            ],
        ];
    }
}