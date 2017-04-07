<?php

namespace TreeSoft\Tests\Specifications\Schema;

use TreeSoft\Specifications\Schema\LaravelFactory;
use TreeSoft\Specifications\Schema\Loader;
use TreeSoft\Tests\Specifications\Mocks\LoaderMock;
use TreeSoft\Tests\Specifications\TestCase;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ValidatorTest extends TestCase
{
    /**
     * @var LaravelFactory $factory
     */
    private $factory;

    public function testNesting()
    {
        $validator = $this->factory->validator([
            'name' => 'Sergei',
            'phone' => '+7-913-...',
            'company' => [
                'name' => 'TreeSoft',
            ],
        ], 'schema://entities/client');

        $this->assertTrue($validator->fails());
    }

    protected function setUp()
    {
        parent::setUp();

        $fixture = dirname(__DIR__) . '/Fixtures/schema';

        $this->app->instance(Loader::class, new LoaderMock([
            'entities/client' => "{$fixture}/client.json",
            'entities/company' => "{$fixture}/company.json",
        ]));

        $this->factory = $this->app->make(LaravelFactory::class);
    }
}
