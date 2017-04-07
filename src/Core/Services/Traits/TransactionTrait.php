<?php

namespace TreeSoft\Specifications\Core\Services\Traits;

use TreeSoft\Specifications\Core\Interfaces\TransactionInterface;

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
     * @return mixed
     */
    protected function executeInTransaction()
    {
        $this->transaction->start();

        $result = parent::execute();

        $this->transaction->commit();

        return $result;
    }
}
