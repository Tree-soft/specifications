<?php

namespace Mildberry\Tests\Specifications\Transforming\Fillers;

use Mildberry\Specifications\Transforming\Fillers\SetterFiller;
use Mildberry\Tests\Specifications\TestCase;

/**
 * Class SetterFillerTest.
 */
class SetterFillerTest extends TestCase
{
    /**
     * @var SetterFiller
     */
    private $filler;

    /**
     * @dataProvider fieldsProvider
     *
     * @param string $expected
     * @param string $field
     */
    public function testSetter(string $field, string $expected)
    {
        $this->assertEquals($expected, $this->filler->setter($field));
    }

    /**
     * @return array
     */
    public function fieldsProvider()
    {
        return [
            'simple' => [
                'field', 'setField',
            ],
            'multi' => [
                'testField', 'setTestField',
            ],
            'underscore' => [
                'test_field', 'setTestField',
            ],
        ];
    }

    protected function setUp()
    {
        parent::setUp();

        $this->filler = $this->app->make(SetterFiller::class);
    }
}
