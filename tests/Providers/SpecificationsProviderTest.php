<?php

namespace TreeSoft\Tests\Specifications\Providers;

use TreeSoft\Specifications\Core\Interfaces\RepositoryFactoryInterface;
use TreeSoft\Specifications\Core\Interfaces\TransactionInterface;
use TreeSoft\Specifications\DAL\Eloquent\TransactionManager;
use TreeSoft\Specifications\DAL\Factories\DefaultFactory;
use TreeSoft\Specifications\Generators\OutputInterface;
use TreeSoft\Specifications\Providers\SpecificationsProvider;
use TreeSoft\Specifications\Schema\LaravelFactory;
use TreeSoft\Specifications\Schema\Loader;
use TreeSoft\Specifications\Checkers\EntityChecker;
use TreeSoft\Specifications\Schema\TransformerLoader;
use TreeSoft\Specifications\Support\FileWriter;
use TreeSoft\Tests\Specifications\Support\PublishedDataAssertionTrait;
use TreeSoft\Tests\Specifications\TestCase;
use Illuminate\Contracts\Config\Repository as Config;
use Rnr\Resolvers\Providers\ResolversProvider;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class SpecificationsProviderTest extends TestCase
{
    use PublishedDataAssertionTrait;

    /**
     * @var Config
     */
    private $config;

    public function testPublishData()
    {
        /**
         * @var SpecificationsProvider $provider
         */
        $provider = $this->app->getProvider(SpecificationsProvider::class);

        $this->assertPublishData($provider);
        $this->assertNotEmpty($this->app->getProvider(ResolversProvider::class));
    }

    protected function setUp()
    {
        parent::setUp();

        $this->config = $this->app->make(Config::class);
    }

    public function testLoader()
    {
        /**
         * @var Loader $loader
         */
        $loader = $this->app->make(Loader::class);

        $this->assertEquals($this->config->get('specifications.path'), $loader->getPath());
    }

    public function testTransformLoader()
    {
        /**
         * @var TransformerLoader $loader
         */
        $loader = $this->app->make(TransformerLoader::class);

        $this->assertEquals($this->config->get('specifications.transform.path'), $loader->getPath());
    }

    public function testSchemaFactory()
    {
        /**
         * @var EntityChecker $specification
         */
        $specification = $this->app->make(EntityChecker::class);

        $this->assertInstanceOf(LaravelFactory::class, $specification->getFactory());
    }

    public function testOutputInterface()
    {
        $output = $this->app->make(OutputInterface::class);

        $this->assertInstanceOf(FileWriter::class, $output);
    }

    public function testRepositoryFactory()
    {
        $factory = $this->app->make(RepositoryFactoryInterface::class);

        $this->assertInstanceOf(DefaultFactory::class, $factory);
    }

    public function testDALBindings()
    {
        $transaction = $this->app->make(TransactionInterface::class);

        $this->assertInstanceOf(TransactionManager::class, $transaction);
    }
}
