<?php

namespace TreeSoft\Specifications\DAL\Laravel;

use TreeSoft\Specifications\Core\Interfaces\CacheInterface;
use Illuminate\Contracts\Cache\Repository as LaraverCache;

/**
 * Class Cache
 */
class Cache implements CacheInterface
{
    /**
     * @var LaraverCache
     */
    private $cache;

    /**
     * Cache constructor.
     * @param LaraverCache $cache
     */
    public function __construct(LaraverCache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param string $key
     * @param mixed $data
     * @param int $minutes
     */
    public function set(string $key, $data, int $minutes)
    {
        $this->cache->put($key, $data, $minutes);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->cache->get($key);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return $this->cache->has($key);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function forget(string $key)
    {
        return $this->cache->forget($key);
    }
}