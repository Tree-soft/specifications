<?php

namespace Mildberry\Tests\Specifications\Providers;

use Mildberry\Specifications\Providers\JsonSchemaProvider;
use Mildberry\Specifications\Schema\LaravelFactory;
use Mildberry\Specifications\Schema\Loader;
use Mildberry\Specifications\Checkers\EntityChecker;
use Mildberry\Tests\Specifications\Support\PublishedDataAssertionTrait;
use Mildberry\Tests\Specifications\TestCase;
use Illuminate\Contracts\Config\Repository as Config;
use Rnr\Resolvers\Providers\ResolversProvider;

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

        $this->assertNotEmpty($this->app->getProvider(ResolversProvider::class));

        /**
         * @var Config $config
         */
        $config = $this->app->make(Config::class);

        /**
         * @var Loader $loader
         */
        $loader = $this->app->make(Loader::class);

        $this->assertEquals($config->get('specifications.path'), $loader->getPath());

        /**
         * @var EntityChecker $specification
         */
        $specification = $this->app->make(EntityChecker::class);

        $this->assertInstanceOf(LaravelFactory::class, $specification->getFactory());
    }
}
