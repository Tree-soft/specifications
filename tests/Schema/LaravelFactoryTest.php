<?php

namespace Mildberry\Tests\Specifications\Schema;

use Mildberry\Specifications\Schema\LaravelFactory;
use Mildberry\Specifications\Schema\Loader;
use Mildberry\Tests\Specifications\TestCase;
use Illuminate\Contracts\Config\Repository as Config;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class LaravelFactoryTest extends TestCase
{
    /**
     * @var LaravelFactory $factory
     */
    private $factory;

    public function testDereferencer()
    {
        $dereferencer = $this->factory->dereferencer();

        /**
         * @var Loader $loader
         */
        $loader = $dereferencer->getLoader('schema');

        $this->assertInstanceOf(Loader::class, $loader);

        /**
         * @var Config $config
         */
        $config = $this->app->make(Config::class);

        $this->assertEquals($config->get('specifications.path'), $loader->getPath());
    }

    protected function setUp()
    {
        parent::setUp();

        $this->factory = $this->app->make(LaravelFactory::class);
    }
}
