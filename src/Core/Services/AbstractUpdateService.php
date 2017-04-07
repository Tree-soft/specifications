<?php

namespace TreeSoft\Specifications\Core\Services;

use TreeSoft\Specifications\Core\Interfaces\TransactionInterface;
use TreeSoft\Specifications\Core\Services\Traits\TransactionTrait;
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

        return parent::afterFailedExecution($e);
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        return $this->executeInTransaction();
    }
}
