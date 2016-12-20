<?php

namespace Mildberry\Specifications\Provider;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Mildberry\Specifications\Schema\Loader;
use Mildberry\Specifications\Support\PublisherInterface;
use Illuminate\Contracts\Config\Repository as Config;

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
        return [
            'config' => [
                dirname(dirname(__DIR__)).'/config/specifications.php' =>
                    $this->app->configPath().'/specifications.php',
            ],
        ];
    }

    public function register()
    {
        $this->app->singleton(Loader::class, function (Application $app) {
            $config = $app->make(Config::class);

            $loader = new Loader();

            $loader->setPath($config->get('specifications.path'));

            return $loader;
        });
    }
}
