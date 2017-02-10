<?php

namespace Mildberry\Specifications\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Mildberry\Specifications\Core\Interfaces\RepositoryFactoryInterface;
use Mildberry\Specifications\Core\Interfaces\TransactionInterface;
use Mildberry\Specifications\Generators\OutputInterface;
use Mildberry\Specifications\Support\Transformers\EntityTransformer;
use Mildberry\Specifications\Schema\LaravelFactory;
use Mildberry\Specifications\Schema\Loader;
use Mildberry\Specifications\Checkers\AbstractChecker;
use Mildberry\Specifications\Schema\TransformerLoader;
use Mildberry\Specifications\Support\FileWriter;
use Mildberry\Specifications\Support\PublisherInterface;
use Illuminate\Contracts\Config\Repository as Config;
use Rnr\Resolvers\Providers\ResolversProvider;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class SpecificationsProvider extends ServiceProvider implements PublisherInterface
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @return array
     */
    public function getPublishingData()
    {
        $root = dirname(dirname(__DIR__));

        return [
            'config' => [
                "{$root}/config/specifications.php" =>
                    $this->app->configPath() . '/specifications.php',
                "{$root}/config/dal.php" =>
                    $this->app->configPath() . '/dal.php',
            ],
            'schema' => [
                "{$root}/resources/schema" =>
                    $this->app->resourcePath() . '/schema',
            ],
            'transform' => [
                "{$root}/resources/transform" =>
                    $this->app->resourcePath() . '/transform',
            ],
        ];
    }

    public function register()
    {
        $this->publishData();

        $this->app->register(ResolversProvider::class);

        $this->app->alias(FileWriter::class, OutputInterface::class);

        $this->registerSchema();
        $this->registerResolvers();
        $this->registerDAL();
    }

    protected function publishData()
    {
        foreach ($this->getPublishingData() as $tag => $data) {
            $this->publishes($data, $tag);
        }
    }

    protected function registerSchema()
    {
        $this->registerSingletons([
            Loader::class => function (Application $app) {
                $config = $app->make(Config::class);

                $loader = new Loader();

                $loader->setPath($config->get('specifications.path'));

                return $loader;
            },
            TransformerLoader::class => function (Application $app) {
                $config = $app->make(Config::class);

                $loader = new TransformerLoader();

                $loader->setPath($config->get('specifications.transform.path'));

                return $loader;
            },
        ]);
    }

    protected function registerResolvers()
    {
        $resolvings = [
            AbstractChecker::class => function (AbstractChecker $specification) {
                /**
                 * @var LaravelFactory $factory
                 */
                $factory = $this->app->make(LaravelFactory::class);

                $specification->setFactory($factory);
            },
            EntityTransformer::class => function (EntityTransformer $transformer) {
                /**
                 * @var Config $config
                 */
                $config = $this->app->make(Config::class);

                $transformer->setNamespace($config->get('specifications.namespace', '\\'));
            },
        ];

        foreach ($resolvings as $abstract => $callback) {
            $this->app->afterResolving($abstract, $callback);
        }
    }

    protected function registerDAL()
    {
        $this->registerSingletons([
            RepositoryFactoryInterface::class => function (Application $app) {
                $config = $app->make(Config::class);

                $class = $config->get('dal.factory');

                assert(is_a($class, RepositoryFactoryInterface::class, true));

                return $app->make($class);
            },
            TransactionInterface::class => function (Application $app) {
                $config = $app->make(Config::class);

                $class = $config->get('dal.transaction');

                assert(is_a($class, TransactionInterface::class, true));

                return $app->make($class);
            },
        ]);
    }

    /**
     * @param array|callable[] $singletons
     */
    protected function registerSingletons(array $singletons)
    {
        foreach ($singletons as $abstract => $callback) {
            $this->app->singleton($abstract, $callback);
        }
    }
}
