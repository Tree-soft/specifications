<?php

namespace Mildberry\Tests\Specifications\Schema;

use Mildberry\Specifications\Schema\LaravelFactory;
use Mildberry\Specifications\Schema\Loader;
use Mildberry\Tests\Specifications\Mocks\LoaderMock;
use Mildberry\Tests\Specifications\TestCase;

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
                'name' => 'Mildberry',
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
