<?php

namespace Mildberry\Tests\Specifications;

use Illuminate\Foundation\Application;
use Mildberry\Specifications\Providers\SpecificationsProvider;
use Orchestra\Testbench\TestCase as ParentTestCase;
use Illuminate\Contracts\Config\Repository as Config;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class TestCase extends ParentTestCase
{
    /**
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [SpecificationsProvider::class];
    }

    /**
     * @param Application $app
     */
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

        $config->set('specifications.transform.path', $this->getFixturePath('transform'));

        $config->set(
            'container', require dirname(__DIR__) . '/vendor/rnr/resolver-provider/config/container.php'
        );
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function getFixturePath(string $path = '')
    {
        return rtrim(__DIR__ . "/fixtures/{$path}", '/');
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function getResourcePath(string $path = '')
    {
        return rtrim(dirname(__DIR__) . "/resources/{$path}", '/');
    }
}
