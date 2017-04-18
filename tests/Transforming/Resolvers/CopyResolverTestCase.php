<?php

namespace TreeSoft\Tests\Specifications\Transforming\Resolvers;

use TreeSoft\Specifications\Support\DataPreparator;
use TreeSoft\Specifications\Transforming\Resolvers\CopyResolver;
use TreeSoft\Tests\Specifications\TestCase;

/**
 * Class CopyResolverTestCase.
 */
class CopyResolverTestCase extends TestCase
{
    /**
     * @var CopyResolver
     */
    private $resolver;

    /**
     * @dataProvider equalSchemasProvider
     *
     * @param mixed $from
     * @param mixed $to
     */
    public function testIsEqualSchema($from, $to)
    {
        $this->assertTrue($this->resolver->isEqualSchema($from, $to));
    }

    /**
     * @return array
     */
    public function equalSchemasProvider()
    {
        $preparator = new DataPreparator();

        return [
            'simple-types' => ['integer', 'integer'],
            'schema' => ['schema://entity/client', 'schema://entity/client'],
            'as-object' => [
                $preparator->prepare(['type' => 'string']),
                $preparator->prepare(['type' => 'string']),
            ],
            'additional-fields' => [
                $preparator->prepare(['type' => 'integer']),
                $preparator->prepare([
                    'type' => 'integer',
                    'minimum' => 1,
                ]),
            ],
            'array' => [
                $preparator->prepare([
                    'type' => 'array',
                    'items' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'integer',
                        ],
                    ],
                ]),
                $preparator->prepare(['type' => 'array']),
            ],
            'nested-array' => [
                $preparator->prepare([
                    'type' => 'array',
                    'items' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'integer',
                        ],
                    ],
                ]),
                $preparator->prepare([
                    'type' => 'array',
                    'items' => [
                        'type' => 'array',
                    ],
                ]),
            ],
            'oneOf' => [
                $preparator->prepare([
                    'oneOf' => [
                        [
                            'id' => 'schema://common/id',
                            'oneOf' => [
                                ['type' => 'integer'],
                                ['type' => 'null'],
                            ],
                        ],
                        ['type' => 'null'],
                    ],
                ]),
                $preparator->prepare([
                    'oneOf' => [
                        ['type' => 'null'],
                        [
                            'id' => 'schema://common/id',
                            'oneOf' => [
                                ['type' => 'integer'],
                                ['type' => 'null'],
                            ],
                        ],
                    ],
                ]),
            ],
        ];
    }

    /**
     * @dataProvider notEqualSchemasProvider
     *
     * @param mixed $from
     * @param mixed $to
     */
    public function testIsNotEqualSchema($from, $to)
    {
        $this->assertFalse($this->resolver->isEqualSchema($from, $to));
    }

    /**
     * @return array
     */
    public function notEqualSchemasProvider()
    {
        $preparator = new DataPreparator();

        return [
            'simple-types' => ['integer', 'string'],
            'schema' => ['schema://entity/client', 'schema://entity/clientFull'],
            'as-object' => [
                $preparator->prepare(['type' => 'string']),
                $preparator->prepare(['type' => 'integer']),
            ],
            'additional-fields' => [
                $preparator->prepare(['type' => 'integer']),
                $preparator->prepare([
                    'type' => 'integer',
                    'id' => 'schema://common/id',
                ]),
            ],
            'array' => [
                $preparator->prepare([
                    'type' => 'array',
                    'items' => [
                        'type' => 'integer',
                    ],
                ]),
                $preparator->prepare([
                    'type' => 'array',
                    'items' => [
                        'type' => 'string',
                    ],
                ]),
            ],
            'array-nested' => [
                $preparator->prepare([
                    'type' => 'array',
                    'items' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'integer',
                        ],
                    ],
                ]),
                $preparator->prepare([
                    'type' => 'array',
                    'items' => [
                        'type' => 'array',
                        'items' => [
                            'type' => 'string',
                        ],
                    ],
                ]),
            ],
            'oneOf' => [
                $preparator->prepare([
                    'oneOf' => [
                        [
                            'id' => 'schema://common/id',
                            'oneOf' => [
                                ['type' => 'integer'],
                                ['type' => 'null'],
                            ],
                        ],
                        ['type' => 'null'],
                    ],
                ]),
                $preparator->prepare([
                    'oneOf' => [
                        [
                            'id' => 'schema://entities/location/city',
                            'type' => 'object',
                            'properties' => [
                                'id' => ['type' => 'integer'],
                            ],
                        ],
                        ['type' => 'null'],
                    ],
                ]),
            ],
        ];
    }

    protected function setUp()
    {
        parent::setUp();

        $this->resolver = $this->app->make(CopyResolver::class);
    }
}
