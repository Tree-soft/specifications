<?php

namespace Mildberry\Specifications\Core\Services;

use Mildberry\Specifications\Core\Interfaces\CacheInterface;
use Mildberry\Specifications\Core\Services\Traits\CacherTrait;
use Throwable;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class AbstractCacheService extends AbstractService
{
    use CacherTrait;

    /**
     * @return string
     */
    abstract public function getKey(): string;

    public function afterResolving()
    {
        parent::afterResolving();

        $this->cacher = $this->container->make(CacheInterface::class);
    }

    /**
     * @param callable $executionCallback
     *
     * @throws Throwable
     *
     * @return mixed
     */
    protected function wrapExecution($executionCallback)
    {
        return $this->cacheExecution($executionCallback);
    }
}
