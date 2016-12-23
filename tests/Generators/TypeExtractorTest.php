<?php

namespace Mildberry\Tests\Specifications\Generators;

use Mildberry\Specifications\Generators\ClassBuilders\TypeExtractor;
use Mildberry\Tests\Specifications\TestCase;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class TypeExtractorTest extends TestCase
{
    /**
     * @var TypeExtractor
     */
    private $extractor;

    /**
     * @dataProvider typesProvider
     *
     * @param string|array $excepted
     * @param $schema
     */
    public function testConvertType($excepted, $schema)
    {
        $this->assertEquals($excepted, $this->extractor->extract($schema));
    }

    public function typesProvider()
    {
        return [
            'null' => [
                'null', (object) [
                    'type' => 'null',
                ],
            ],
            'boolean' => [
                'bool', (object) [
                    'type' => 'boolean',
                ],
            ],
            'object' => [
                'object', (object) [
                    'type' => 'object',
                ],
            ],
            'array' => [
                'array', (object) [
                    'type' => 'array',
                ],
            ],
            'number' => [
                'float', (object) [
                    'type' => 'float',
                ],
            ],
            'integer' => [
                'int', (object) [
                    'type' => 'integer',
                ],
            ],
            'string' => [
                'string', (object) [
                    'type' => 'string',
                ],
            ],
            'multiple-all-of' => [
                ['string', 'null'], (object) [
                    'type' => (object) [
                        'allOf' => [
                            (object) ['type' => 'string'],
                            (object) ['type' => 'null'],
                        ],
                    ],
                ],
            ],
            'multiple-any-of' => [
                ['string', 'null'], (object) [
                    'type' => (object) [
                        'anyOf' => [
                            (object) ['type' => 'string'],
                            (object) ['type' => 'null'],
                        ],
                    ],
                ],
            ],
            'multiple-one-of' => [
                ['string', 'null'], (object) [
                    'type' => (object) [
                        'oneOf' => [
                            (object) ['type' => 'string'],
                            (object) ['type' => 'null'],
                        ],
                    ],
                ],
            ],
            'multiple-nested' => [
                ['string', 'null'], (object) [
                    'type' => (object) [
                        'oneOf' => [
                            (object) ['type' => 'string'],
                            (object) [
                                'type' => (object) [
                                    'allOf' => [
                                        (object) ['type' => 'string'],
                                        (object) ['type' => 'null'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
//            'class' => [],
        ];
    }

    protected function setUp()
    {
        parent::setUp();

        $this->extractor = new TypeExtractor();
    }
}
