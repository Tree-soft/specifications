<?php

namespace Mildberry\Tests\Specifications\Testing\Core;

use Mildberry\Specifications\Core\Services\AbstractService;
use Mildberry\Specifications\Support\Testing\Core\MockServiceTrait;
use Mildberry\Tests\Specifications\TestCase;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class MockServiceTraitTest extends TestCase
{
    use MockServiceTrait;

    public function testMock()
    {
        $service = $this->mockService(AbstractService::class, 5);

        $this->assertSame($service, $this->app->make(AbstractService::class));
        $this->assertEquals($service->execute(), 5);
    }
}
