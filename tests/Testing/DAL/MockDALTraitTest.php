<?php

namespace TreeSoft\Tests\Specifications\Testing\DAL;

use TreeSoft\Specifications\Core\Interfaces\RepositoryFactoryInterface;
use TreeSoft\Specifications\Core\Interfaces\TransactionInterface;
use TreeSoft\Specifications\Support\Testing\DAL\MockDALTrait;
use TreeSoft\Specifications\Support\Testing\DAL\RepositoryFactoryMock;
use TreeSoft\Specifications\Support\Testing\DAL\TransactionMock;
use TreeSoft\Tests\Specifications\TestCase;

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
