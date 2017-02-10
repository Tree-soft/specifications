<?php

namespace Mildberry\Tests\Specifications\Providers;

use Mildberry\Specifications\Core\Interfaces\RepositoryFactoryInterface;
use Mildberry\Specifications\Core\Interfaces\TransactionInterface;
use Mildberry\Specifications\DAL\Eloquent\TransactionManager;
use Mildberry\Specifications\DAL\Factories\DefaultFactory;
use Mildberry\Specifications\Generators\OutputInterface;
use Mildberry\Specifications\Http\Transformers\EntityTransformer;
use Mildberry\Specifications\Providers\SpecificationsProvider;
use Mildberry\Specifications\Schema\LaravelFactory;
use Mildberry\Specifications\Schema\Loader;
use Mildberry\Specifications\Checkers\EntityChecker;
use Mildberry\Specifications\Schema\TransformerLoader;
use Mildberry\Specifications\Support\FileWriter;
use Mildberry\Tests\Specifications\Support\PublishedDataAssertionTrait;
use Mildberry\Tests\Specifications\TestCase;
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

    public function testTransformer()
    {
        /**
         * @var EntityTransformer $transformer
         */
        $transformer = $this->app->make(EntityTransformer::class);

        $this->assertEquals(
            ltrim($this->config->get('specifications.namespace'), '\\'),
            $transformer->getNamespace()
        );
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
