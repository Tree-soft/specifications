<?php

namespace Mildberry\Specifications\Core\Services\Traits;

use Mildberry\Specifications\Core\Interfaces\TransactionInterface;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
trait TransactionTrait
{
    /**
     * @var TransactionInterface
     */
    private $transaction;

    protected function rollbackTransaction()
    {
        $this->transaction->rollback();
    }

    /**
     * @param $executionCallback
     *
     * @return mixed
     */
    protected function wrapTransactionExecution($executionCallback)
    {
        $this->transaction->start();

        $result = parent::wrapExecution($executionCallback);

        $this->transaction->commit();

        return $result;
    }
}
