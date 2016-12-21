<?php

namespace Mildberry\Tests\Specifications\Specifications;

use Mildberry\Specifications\Objects\RequestInterface;
use Mildberry\Specifications\Specifications\Request\RequestSpecification;
use Mildberry\Tests\Specifications\TestCase;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class RequestSpecificationTest extends TestCase
{
    public function testEmpty()
    {
        $specification = $this->app->make(RequestSpecification::class);

        $specification->check(new class() implements RequestInterface {
            /**
             * @return array
             */
            public function getHeaders(): array
            {
                return [];
            }

            /**
             * @return array
             */
            public function getQuery(): array
            {
                return [];
            }

            /**
             * @return array
             */
            public function getData(): array
            {
                return [];
            }
        });
    }
}
