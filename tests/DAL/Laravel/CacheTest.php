<?php

namespace TreeSoft\Tests\Specifications\DAL\Laravel;

use TreeSoft\Specifications\Core\Interfaces\CacheInterface;
use TreeSoft\Specifications\DAL\Laravel\Cache;
use TreeSoft\Tests\Specifications\TestCase;
use Illuminate\Contracts\Cache\Repository as LaravelCache;

/**
 * Class CacheTest
 */
class CacheTest extends TestCase
{
    const VALUE = 1234;
    const KEY = 'test';
    const CACHE_TIME = 1000;
    /**
     * @var CacheInterface
     */
    private $cache;

    public function testsCacheExists() {
        /** @var LaravelCache $cache */
        $cache = $this->app->make(LaravelCache::class);

        $cache
            ->forever(self::KEY, self::VALUE);

        $this->assertEquals($this->cache->get(self::KEY), self::VALUE);
    }

    public function testCacheSave() {
        /** @var LaravelCache $cache */
        $cache = $this->app->make(LaravelCache::class);

        $this->cache
            ->set(self::KEY, self::VALUE, self::CACHE_TIME);

        $this->assertEquals($cache->get(self::KEY), self::VALUE);
    }

    public function testHasShouldBeTrue() {
        /** @var LaravelCache $cache */
        $cache = $this->app->make(LaravelCache::class);

        $cache
            ->forever(self::KEY, self::VALUE);

        $this->assertTrue($this->cache->has(self::KEY));
    }

    public function testHasShouldBeFalse() {
        $this->assertFalse($this->cache->has(self::KEY));
    }

    public function testForget() {
        /** @var LaravelCache $cache */
        $cache = $this->app->make(LaravelCache::class);

        $cache
            ->forever(self::KEY, self::VALUE);

        $this->cache->forget(self::KEY);

        $this->assertFalse($cache->has(self::KEY));
    }

    protected function setUp()
    {
        parent::setUp();

        $this->cache = $this->app->make(Cache::class);
    }


}