<?php

namespace Mildberry\Tests\Specifications\Testing\DAL;

use Mildberry\Specifications\Core\Interfaces\RepositoryFactoryInterface;
use Mildberry\Specifications\Core\Interfaces\TransactionInterface;
use Mildberry\Specifications\Support\Testing\DAL\MockDALTrait;
use Mildberry\Specifications\Support\Testing\DAL\RepositoryFactoryMock;
use Mildberry\Specifications\Support\Testing\DAL\TransactionMock;
use Mildberry\Tests\Specifications\TestCase;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class MockDALTraitTest extends TestCase
{
    use MockDALTrait;

    public function testDump()
    {
        $factory = $this->app->make(RepositoryFactoryInterface::class);
        $this->assertInstanceOf(RepositoryFactoryMock::class, $factory);
        $this->assertSame($this->mocksDAL->factory, $factory);

        $transaction = $this->app->make(TransactionInterface::class);
        $this->assertInstanceOf(TransactionMock::class, $transaction);
        $this->assertSame($this->mocksDAL->transaction, $transaction);
    }
}
