<?php

namespace Mildberry\Tests\Specifications\Schema;

use Mildberry\Specifications\Schema\LaravelFactory;
use Mildberry\Specifications\Schema\Loader;
use Mildberry\Specifications\Schema\TransformerLoader;
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
         * @var Config $config
         */
        $config = $this->app->make(Config::class);

        $loaderManager = $dereferencer->getLoaderManager();
        /**
         * @var Loader $loader
         */
        $loader = $loaderManager->getLoader('schema');

        $this->assertInstanceOf(Loader::class, $loader);
        $this->assertEquals($config->get('specifications.path'), $loader->getPath());

        /**
         * @var Loader $loader
         */
        $loader = $loaderManager->getLoader('transform');

        $this->assertInstanceOf(TransformerLoader::class, $loader);
        $this->assertEquals($config->get('specifications.transform.path'), $loader->getPath());
    }

    protected function setUp()
    {
        parent::setUp();

        $this->factory = $this->app->make(LaravelFactory::class);
    }
}
