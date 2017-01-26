<?php

namespace Mildberry\Specifications\Core\Services;

use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;
use Throwable;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class AbstractService implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @return mixed
     */
    abstract protected function innerExecute();

    public function afterResolving()
    {
    }

    /**
     * @throws Throwable
     *
     * @return mixed
     */
    public function execute()
    {
        return $this->wrapExecution(function () {
            try {
                $result = $this->innerExecute();
            } catch (Throwable $e) {
                $result = $this->afterFailedExecution($e);
            }

            return $result;
        });
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
        return $executionCallback();
    }

    /**
     * @param Throwable $e
     *
     * @throws Throwable
     *
     * @return mixed
     */
    protected function afterFailedExecution(Throwable $e)
    {
        throw $e;
    }
}
