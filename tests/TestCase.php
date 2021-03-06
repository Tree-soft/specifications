<?php

namespace TreeSoft\Tests\Specifications;

use Illuminate\Foundation\Application;
use TreeSoft\Specifications\Providers\SpecificationsProvider;
use TreeSoft\Specifications\Support\Testing\BootstrapTestTrait;
use TreeSoft\Specifications\Support\Testing\FixtureLoaderTrait;
use Orchestra\Testbench\TestCase as ParentTestCase;
use Illuminate\Contracts\Config\Repository as Config;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class TestCase extends ParentTestCase
{
    use FixtureLoaderTrait;
    use BootstrapTestTrait;

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

        $config->set(
            'dal', require dirname(__DIR__) . '/config/dal.php'
        );

        $config->set('specifications.transform.path', $this->getFixturePath('transform'));
    }

    /**
     * @param string $path
     *
     * @return string
     */
    protected function getFixturePath(string $path = '')
    {
        return rtrim(__DIR__ . "/Fixtures/{$path}", '/');
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

    /**
     * @param string $fn
     *
     * @return string
     */
    protected function getFixtureFilename(string $fn): string
    {
        return implode(DIRECTORY_SEPARATOR, [__DIR__, 'Fixtures', 'data', $fn]);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->boot();
    }
}
