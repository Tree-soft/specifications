<?php

namespace Mildberry\Tests\Specifications\Mocks;

use Mildberry\Specifications\Core\Interfaces\CacheInterface;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class CacheMock implements CacheInterface
{
    use CallsTrait;

    /**
     * @param string $key
     * @param mixed $data
     */
    public function set(string $key, $data)
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
