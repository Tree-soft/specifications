<?php

namespace Mildberry\Tests\Specifications\Core\Services;

use Mildberry\Specifications\Core\Services\AbstractService;
use Mildberry\Tests\Specifications\Core\TestCase;
use Exception;
use Throwable;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class AbstractServiceTest extends TestCase
{
    public function testSuccess()
    {
        /**
         * @var AbstractService|object $service
         */
        $service = new class() extends AbstractService {
            /**
             * @return string
             */
            protected function innerExecute()
            {
                return 'executed';
            }
        };

        $result = $service->execute();

        $this->assertEquals('executed', $result);
    }

    public function testFailure()
    {
        /**
         * @var AbstractService|object $service
         */
        $service = new class() extends AbstractService {
            /**
             * @throws Exception
             */
            protected function innerExecute()
            {
                throw new Exception('executed with failures');
            }

            /**
             * @param Throwable $e
             *
             * @return mixed|void
             */
            protected function afterFailedExecution(Throwable $e)
            {
                parent::afterFailedExecution($e);
            }
        };

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('executed with failures');

        $service->execute();
    }

    public function testCatch()
    {
        /**
         * @var AbstractService|object $service
         */
        $service = new class() extends AbstractService {
            /**
             * @throws Exception
             */
            protected function innerExecute()
            {
                throw new Exception('executed with failures');
            }

            /**
             * @param Throwable $e
             *
             * @return mixed
             */
            protected function afterFailedExecution(Throwable $e)
            {
                TestCase::assertEquals(Exception::class, get_class($e));
                TestCase::assertEquals('executed with failures', $e->getMessage());

                return 'executed';
            }
        };

        $result = $service->execute();

        $this->assertEquals('executed', $result);
    }
}
