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
        $this->assertInstanceOf(
            RepositoryFactoryMock::class, $this->app->make(RepositoryFactoryInterface::class)
        );

        $this->assertInstanceOf(
            TransactionMock::class, $this->app->make(TransactionInterface::class)
        );
    }
}
