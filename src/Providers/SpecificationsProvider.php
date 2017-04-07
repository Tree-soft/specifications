<?php

namespace TreeSoft\Specifications\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use TreeSoft\Specifications\Core\Interfaces\RepositoryFactoryInterface;
use TreeSoft\Specifications\Core\Interfaces\TransactionInterface;
use TreeSoft\Specifications\Generators\OutputInterface;
use TreeSoft\Specifications\Support\Resolvers\SpecificationsNamespace\NamespaceAwareInterface;
use TreeSoft\Specifications\Support\Resolvers\SpecificationsNamespace\NamespaceResolver;
use TreeSoft\Specifications\Support\Resolvers\Transform\TransformAwareInterface;
use TreeSoft\Specifications\Support\Resolvers\Transform\TransformResolver;
use TreeSoft\Specifications\Schema\LaravelFactory;
use TreeSoft\Specifications\Schema\Loader;
use TreeSoft\Specifications\Checkers\AbstractChecker;
use TreeSoft\Specifications\Schema\TransformerLoader;
use TreeSoft\Specifications\Support\FileWriter;
use TreeSoft\Specifications\Support\PublisherInterface;
use Illuminate\Contracts\Config\Repository as Config;
use Rnr\Resolvers\Manage\ResolverConfigurator;
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
        /**
         * @var ResolverConfigurator $configurator
         */
        $configurator = $this->app->make(ResolverConfigurator::class);

        $configurator
            ->setResolver(TransformAwareInterface::class, TransformResolver::class)
            ->setResolver(NamespaceAwareInterface::class, NamespaceResolver::class);

        $resolvings = [
            AbstractChecker::class => function (AbstractChecker $specification) {
                /**
                 * @var LaravelFactory $factory
                 */
                $factory = $this->app->make(LaravelFactory::class);

                $specification->setFactory($factory);
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
