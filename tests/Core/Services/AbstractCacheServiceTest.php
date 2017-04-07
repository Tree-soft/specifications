<?php

namespace TreeSoft\Tests\Specifications\Core\Services;

use TreeSoft\Specifications\Core\Interfaces\CacheInterface;
use TreeSoft\Specifications\Core\Services\AbstractCacheService;
use TreeSoft\Specifications\Core\Services\AbstractService;
use TreeSoft\Tests\Specifications\Core\TestCase;
use TreeSoft\Tests\Specifications\Mocks\CacheMock;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class AbstractCacheServiceTest extends TestCase
{
    public function testNonexistentCache()
    {
        $cacher = new CacheMock();

        $this->app->instance(CacheInterface::class, $cacher);

        /**
         * @var AbstractService $service
         */
        $service = $this->createService();

        $result = $service->execute();
        $key = $service->keyByObject($service, $service->getKey());

        $this->assertEquals('executed', $result);
        $this->assertEquals([
            [
                'method' => 'has',
                'args' => [$key],
            ],
            [
                'method' => 'set',
                'args' => [$key, 'executed'],
            ],
        ], $cacher->calls);
    }

    public function testExistentCache()
    {
        $cacher = new class() extends CacheMock {
            /**
             * @param string $key
             *
             * @return string
             */
            public function get(string $key)
            {
                parent::get($key);

                return 'from cache';
            }

            /**
             * @param string $key
             *
             * @return bool
             */
            public function has(string $key): bool
            {
                parent::has($key);

                return true;
            }
        };

        $this->app->instance(CacheInterface::class, $cacher);

        /**
         * @var AbstractService $service
         */
        $service = $this->createService();

        $result = $service->execute();
        $key = $service->keyByObject($service, $service->getKey());

        $this->assertEquals('from cache', $result);
        $this->assertEquals([
            [
                'method' => 'has',
                'args' => [$key],
            ],
            [
                'method' => 'get',
                'args' => [$key],
            ],
        ], $cacher->calls);
    }

    /**
     * @return AbstractCacheService $service
     */
    protected function createService(): AbstractCacheService
    {
        /**
         * @var AbstractCacheService $service
         */
        $service = new class() extends AbstractCacheService {
            /**
             * @return string
             */
            public function getKey(): string
            {
                return 'test';
            }

            /**
             * @return string
             */
            protected function innerExecute()
            {
                return 'executed';
            }
        };

        $service
            ->setContainer($this->app)
            ->afterResolving();

        return $service;
    }
}
