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
        ];
    }

    protected function setUp()
    {
        parent::setUp();

        $this->resolver = $this->app->make(CopyResolver::class);
    }
}
