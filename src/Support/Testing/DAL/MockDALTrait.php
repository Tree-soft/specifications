<?php

namespace Mildberry\Specifications\Support\Testing\DAL;

use Illuminate\Container\Container;
use Mildberry\Specifications\Core\Interfaces\RepositoryFactoryInterface;
use Mildberry\Specifications\Core\Interfaces\TransactionInterface;

/**
 * @author Sergei Melnikov <me@rnr.name>
 *
 * @property Container $app
 */
trait MockDALTrait
{
    /**
     * @var object
     */
    protected $mocksDAL;

    /**
     * @var RepositoryFactoryMock
     */
    protected $factory;

    protected function bootstrapDAL()
    {
        $transaction = $this->app->make(TransactionMock::class);
        $this->app->instance(TransactionInterface::class, $transaction);

        $factory = $this->app->make(RepositoryFactoryMock::class);
        $this->app->instance(RepositoryFactoryInterface::class, $factory);

        $this->mocksDAL = (object) [
            'transaction' => $transaction,
            'factory' => $factory,
        ];
    }
}
