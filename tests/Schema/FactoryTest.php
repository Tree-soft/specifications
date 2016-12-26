<?php

namespace Mildberry\Tests\Specifications\Schema;

use League\JsonGuard\Reference;
use Mildberry\Specifications\Schema\Factory;
use Mildberry\Specifications\Schema\LaravelFactory;
use Mildberry\Specifications\Schema\Loader;
use Mildberry\Tests\Specifications\Mocks\LoaderMock;
use Mildberry\Tests\Specifications\TestCase;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class FactoryTest extends TestCase
{
    /**
     * @var Factory
     */
    private $factory;

    public function testResolveReference()
    {
        $schema = $this->factory->schema('schema://entities/client');

        $this->assertNotInstanceOf(Reference::class, $schema->properties->company);
    }

    public function setUp()
    {
        parent::setUp();

        $this->factory = $this->app->make(LaravelFactory::class);

        $this->app->instance(Loader::class, new LoaderMock([
            'entities/company' => $this->getFixturePath('schema/company.json'),
            'entities/client' => $this->getFixturePath('schema/client.json'),
        ]));
    }
}
