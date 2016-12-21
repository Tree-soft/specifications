<?php

namespace Mildberry\Tests\Specifications;

use Mildberry\Specifications\Providers\JsonSchemaProvider;
use Orchestra\Testbench\TestCase as ParentTestCase;
use Illuminate\Contracts\Config\Repository as Config;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class TestCase extends ParentTestCase
{
    protected function getPackageProviders($app)
    {
        return [JsonSchemaProvider::class];
    }

    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        /**
         * @var Config $config
         */
        $config = $app->make(Config::class);

        $config->set(
            'specifications', require dirname(__DIR__) . '/config/specifications.php'
        );

        $config->set(
            'container', require dirname(__DIR__) . '/vendor/rnr/resolver-provider/config/container.php'
        );
    }
}
