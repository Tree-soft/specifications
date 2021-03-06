<?php

namespace TreeSoft\Tests\Specifications\Mocks;

use TreeSoft\Specifications\Core\Interfaces\CacheInterface;
use TreeSoft\Specifications\Support\Testing\CallsTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class CacheMock implements CacheInterface
{
    use CallsTrait;

    /**
     * @param string $key
     * @param mixed $data
     * @param int $minutes
     */
    public function set(string $key, $data, int $minutes)
    {
        $this->_handle(__FUNCTION__, $key, $data);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        $this->_handle(__FUNCTION__, $key);

        return null;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        $this->_handle(__FUNCTION__, $key);

        return false;
    }

    /**
     * @param string $key
     */
    public function forget(string $key)
    {
        $this->_handle(__FUNCTION__, $key);
    }
}
