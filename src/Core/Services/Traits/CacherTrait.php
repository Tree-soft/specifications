<?php

namespace Mildberry\Specifications\Core\Services\Traits;

use Mildberry\Specifications\Core\Interfaces\CacheInterface;
use Throwable;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
trait CacherTrait
{
    /**
     * @var CacheInterface
     */
    private $cacher;

    /**
     * @return string
     */
    abstract public function getKey(): string;

    /**
     * @param callable $executionCallback
     *
     * @throws Throwable
     *
     * @return mixed
     */
    protected function cacheExecution($executionCallback)
    {
        $key = sha1(get_class($this) . $this->getKey());

        if ($this->cacher->has($key)) {
            return $this->cacher->get($key);
        }

        $result = parent::wrapExecution($executionCallback);

        $this->cacher->set($key, $result);

        return $result;
    }
}
