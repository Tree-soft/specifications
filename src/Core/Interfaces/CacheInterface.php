<?php

namespace TreeSoft\Specifications\Core\Interfaces;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
interface CacheInterface
{
    /**
     * @param string $key
     * @param mixed $data
     * @param int $minutes
     *
     * @return
     */
    public function set(string $key, $data, int $minutes);

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key);

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * @param string $key
     */
    public function forget(string $key);
}
