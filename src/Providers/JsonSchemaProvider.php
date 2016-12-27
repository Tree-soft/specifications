<?php

namespace Mildberry\Specifications\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Mildberry\Specifications\Generators\OutputInterface;
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
class JsonSchemaProvider extends ServiceProvider implements PublisherInterface
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
            ],
            'schema' => [
                "{$root}/resources/schema" =>
                    $this->app->resourcePath() . '/schema',
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
    }

    protected function publishData()
    {
        foreach ($this->getPublishingData() as $tag => $data) {
            $this->publishes($data, $tag);
        }
    }
}
