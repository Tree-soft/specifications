<?php

namespace Mildberry\Tests\Specifications;

use Mildberry\Specifications\Provider\JsonSchemaProvider;
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

    protected function getEnvironmentSetUp($app)
    {
        /**
         * @var Config $config
         */
        $config = $app->make(Config::class);

        $config->set('specifications.path', 'test');
    }
}
