<?php

namespace TreeSoft\Tests\Specifications\Support;

use TreeSoft\Specifications\Support\OrderedList;
use TreeSoft\Tests\Specifications\TestCase;

class OrderedListTest extends TestCase
{
    /**
     * @var OrderedList
     */
    private $list;

    /**
     * @dataProvider elementsProvider
     *
     * @param array $expected
     * @param string $name
     * @param string|null $before
     */
    public function testAdd(array $expected, string $name, string $before = null)
    {
        $this->list->add($name, $before);

        $this->assertEquals($expected, $this->list->items());
    }

    /**
     * @return array
     */
    public function elementsProvider()
    {
        return [
            'last' => [
                ['equal', 'json', 'simple', 'null', 'complex', 'array', 'json2'],
                'json2',
            ],
            'before' => [
                ['equal', 'json', 'json2', 'simple', 'null', 'complex', 'array'],
                'json2', 'simple',
            ],
            'first' => [
                ['json2', 'equal', 'json', 'simple', 'null', 'complex', 'array'],
                'json2', 'equal',
            ],
        ];
    }

    public function testRemove()
    {
        $this->list->remove('json');
        $this->assertFalse(isset($this->list['json']));
    }

    protected function setUp()
    {
        parent::setUp();
        $this->list = new OrderedList([
            'equal', 'json', 'simple', 'null', 'complex', 'array',
        ]);
    }
}
