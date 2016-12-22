<?php

namespace Mildberry\Tests\Specifications\Support;

use Mildberry\Specifications\Support\DatePreparator;
use Mildberry\Tests\Specifications\TestCase;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class DatePreparatorTest extends TestCase
{
    /**
     * @var DatePreparator
     */
    private $preparator;

    /**
     * @dataProvider dataProvider
     *
     * @param mixed $expected
     * @param mixed $data
     */
    public function testSimple($expected, $data)
    {
        $this->assertEquals($expected, $this->preparator->prepare($data));
    }

    public function dataProvider()
    {
        return [
            'simple' => [
                1, 1,
            ],
            'array-to-object' => [
                (object) [
                    'data' => 1234,
                ],
                [
                    'data' => 1234,
                ],
            ],
            'nested-array-to-object' => [
                (object) [
                    'data' => 1234,
                    'nested' => (object) [
                        'data' => 5,
                    ],
                ], [
                    'data' => 1234,
                    'nested' => [
                        'data' => 5,
                    ],
                ],
            ],
        ];
    }

    protected function setUp()
    {
        parent::setUp();

        $this->preparator = $this->app->make(DatePreparator::class);
    }
}
