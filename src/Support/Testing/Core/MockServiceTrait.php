<?php

namespace Mildberry\Specifications\Support\Testing\Core;

use Mildberry\Specifications\Core\Services\AbstractService;
use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_Exception;
use Illuminate\Container\Container;

/**
 * @author Sergei Melnikov <me@rnr.name>
 *
 * @property Container $app
 */
trait MockServiceTrait
{
    /**
     * Returns a test double for the specified class.
     *
     * @param string $originalClassName
     *
     * @throws PHPUnit_Framework_Exception
     *
     * @return PHPUnit_Framework_MockObject_MockObject
     *
     * @since Method available since Release 5.4.0
     */
    abstract protected function createMock($originalClassName);

    /**
     * @param mixed $result
     * @param string $class
     *
     * @return AbstractService
     */
    public function createServiceMock($result, string $class = null): AbstractService
    {
        /**
         * @var AbstractService|PHPUnit_Framework_MockObject_MockObject $service
         */
        $service = $this->createMock($class ?? AbstractService::class);

        $service->method('execute')->willReturnCallback(function () use ($result) {
            return value($result);
        });

        if (isset($class)) {
            $this->app->instance($class, $service);
        }

        return $service;
    }
}
