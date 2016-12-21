<?php

namespace Mildberry\Specifications\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Mildberry\Specifications\Schema\LaravelFactory;
use Mildberry\Specifications\Schema\Loader;
use Mildberry\Specifications\Specifications\AbstractSpecification;
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

        $this->app->afterResolving(AbstractSpecification::class, function (AbstractSpecification $specification) {
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
