<?php

namespace TreeSoft\Tests\Specifications\Support;

use TreeSoft\Specifications\Support\DataPreparator;
use TreeSoft\Tests\Specifications\TestCase;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class DatePreparatorTest extends TestCase
{
    /**
     * @var DataPreparator
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

    /**
     * @return array
     */
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

        $this->preparator = $this->app->make(DataPreparator::class);
    }
}
