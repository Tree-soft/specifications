<?php

namespace Mildberry\Tests\Specifications\Providers;

use Mildberry\Specifications\Provider\JsonSchemaProvider;
use Mildberry\Specifications\Schema\Loader;
use Mildberry\Tests\Specifications\Support\PublishedDataAssertionTrait;
use Mildberry\Tests\Specifications\TestCase;
use Illuminate\Contracts\Config\Repository as Config;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class JsonSchemaProviderTest extends TestCase
{
    use PublishedDataAssertionTrait;

    public function testLoading()
    {
        /**
         * @var JsonSchemaProvider $provider
         */
        $provider = $this->app->getProvider(JsonSchemaProvider::class);

        $this->assertPublishData($provider);

        /**
         * @var Config $config
         */
        $config = $this->app->make(Config::class);

        /**
         * @var Loader $loader
         */
        $loader = $this->app->make(Loader::class);

        $this->assertEquals($config->get('specifications.path'), $loader->getPath());
    }

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
