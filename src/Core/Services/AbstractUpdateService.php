<?php

namespace Mildberry\Specifications\Core\Services;

use Mildberry\Specifications\Core\Interfaces\TransactionInterface;
use Mildberry\Specifications\Core\Services\Traits\TransactionTrait;
use Throwable;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class AbstractUpdateService extends AbstractDatabaseService
{
    use TransactionTrait;

    public function afterResolving()
    {
        parent::afterResolving();

        $this->transaction = $this->container->make(TransactionInterface::class);
    }

    /**
     * @param Throwable $e
     *
     * @return mixed
     */
    protected function afterFailedExecution(Throwable $e)
    {
        $this->rollbackTransaction();

        return $this->afterFailedExecution($e);
    }

    /**
     * @param $executionCallback
     *
     * @return mixed
     */
    protected function wrapExecution($executionCallback)
    {
        return $this->wrapTransactionExecution($executionCallback);
    }
}
