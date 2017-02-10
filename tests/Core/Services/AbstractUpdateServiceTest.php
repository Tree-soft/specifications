<?php

namespace Mildberry\Tests\Specifications\Core\Services;

use Mildberry\Specifications\Core\Interfaces\RepositoryFactoryInterface;
use Mildberry\Specifications\Core\Interfaces\RepositoryInterface;
use Mildberry\Specifications\Core\Interfaces\TransactionInterface;
use Mildberry\Specifications\Core\Services\AbstractService;
use Mildberry\Specifications\Core\Services\AbstractUpdateService;
use Mildberry\Tests\Specifications\Core\TestCase;
use Exception;
use Mildberry\Specifications\Support\Testing\DAL\TransactionMock;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class AbstractUpdateServiceTest extends TestCase
{
    public function testSuccess()
    {
        /**
         * @var AbstractService|object $service
         */
        $service = new class() extends AbstractUpdateService {
            /**
             * @return string
             */
            protected function innerExecute()
            {
                return 'executed';
            }
        };

        $transaction = new TransactionMock();

        $this->mock($service, $transaction);

        $result = $service->execute();

        $this->assertEquals('executed', $result);

        $this->assertFalse($transaction->started);
        $this->assertTrue($transaction->wasStarted);
        $this->assertTrue($transaction->wasCommitted);
        $this->assertFalse($transaction->wasRollback);
    }

    public function testFailure()
    {
        /**
         * @var AbstractService|object $service
         */
        $service = new class() extends AbstractUpdateService {
            /**
             * @throws Exception
             */
            protected function innerExecute()
            {
                throw new Exception('executed with failures');
            }
        };

        $transaction = new TransactionMock();

        $this->mock($service, $transaction);

        try {
            $service->execute();
        } catch (Exception $e) {
            if (get_class($e) != Exception::class) {
                throw $e;
            }

            $this->assertEquals($e->getMessage(), 'executed with failures');

            $this->assertFalse($transaction->started);
            $this->assertTrue($transaction->wasStarted);
            $this->assertFalse($transaction->wasCommitted);
            $this->assertTrue($transaction->wasRollback);
        }
    }

    /**
     * @param AbstractService $service
     * @param TransactionInterface $transaction
     */
    protected function mock(AbstractService $service, TransactionInterface $transaction)
    {
        $this->app->instance(TransactionInterface::class, $transaction);

        $this->app->instance(
            RepositoryFactoryInterface::class,
            new class() implements RepositoryFactoryInterface {
                /**
                 * @param string $class
                 *
                 * @throws Exception
                 *
                 * @return RepositoryInterface
                 */
                public function create(string $class): RepositoryInterface
                {
                    throw new Exception();
                }
            }
        );

        $service
            ->setContainer($this->app)
            ->afterResolving();
    }
}
