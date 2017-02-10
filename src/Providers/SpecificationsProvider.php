<?php

namespace Mildberry\Specifications\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Mildberry\Specifications\Generators\OutputInterface;
use Mildberry\Specifications\Http\Transformers\EntityTransformer;
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

        $this->app->singleton(Loader::class, function (Application $app) {
            $config = $app->make(Config::class);

            $loader = new Loader();

            $loader->setPath($config->get('specifications.path'));

            return $loader;
        });

        $this->app->singleton(TransformerLoader::class, function (Application $app) {
            $config = $app->make(Config::class);

            $loader = new TransformerLoader();

            $loader->setPath($config->get('specifications.transform.path'));

            return $loader;
        });

        $this->app->alias(FileWriter::class, OutputInterface::class);

        $this->app->afterResolving(AbstractChecker::class, function (AbstractChecker $specification) {
            /**
             * @var LaravelFactory $factory
             */
            $factory = $this->app->make(LaravelFactory::class);

            $specification->setFactory($factory);
        });

        $this->app->afterResolving(EntityTransformer::class, function (EntityTransformer $transformer) {
            /**
             * @var Config $config
             */
            $config = $this->app->make(Config::class);

            $transformer->setNamespace($config->get('specifications.namespace', '\\'));
        });
    }

    protected function publishData()
    {
        foreach ($this->getPublishingData() as $tag => $data) {
            $this->publishes($data, $tag);
        }
    }
}
