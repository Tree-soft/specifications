<?php

namespace Mildberry\Tests\Specifications\Generators;

use Mildberry\Specifications\Generators\ClassBuilders\TypeExtractor;
use Mildberry\Specifications\Schema\LaravelFactory;
use Mildberry\Specifications\Schema\Loader;
use Mildberry\Tests\Specifications\Mocks\DAL\Entities\Client;
use Mildberry\Tests\Specifications\Mocks\LoaderMock;
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

    /**
     * @return array
     */
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
            'class' => [
                '\Entities\Client', (object) [
                    'type' => 'object',
                    'id' => 'schema://entities/client',
                ],
            ],
            'extending' => [
                '\Entities\Derived\Client', (object) [
                    'id' => 'schema://entities/derived/client',
                    'allOf' => [
                        (object) [
                            'type' => 'object',
                            'id' => 'schema://entities/client',
                        ],
                        (object) ['properties' => (object) [
                            'ext' => ['type' => 'string'],
                        ]],
                    ],
                ],
            ],
            'multiple-one-of' => [
                ['string', 'null'], (object) [
                    'oneOf' => [
                        (object) ['type' => 'string'],
                        (object) ['type' => 'null'],
                    ],
                ],
            ],
//            'multiple-any-of' => [
//                ['string', 'null'], (object) [
//                    'type' => (object) [
//                        'anyOf' => [
//                            (object) ['type' => 'string'],
//                            (object) ['type' => 'null'],
//                        ],
//                    ],
//                ],
//            ],
//            'multiple-nested' => [
//                ['string', 'null'], (object) [
//                    'type' => (object) [
//                        'oneOf' => [
//                            (object) ['type' => 'string'],
//                            (object) [
//                                'type' => (object) [
//                                    'allOf' => [
//                                        (object) ['type' => 'string'],
//                                        (object) ['type' => 'null'],
//                                    ],
//                                ],
//                            ],
//                        ],
//                    ],
//                ],
//            ],
        ];
    }

    public function testBindings()
    {
        $schema = 'schema://dal/models/client';

        $this->extractor
            ->setBindings([
                $schema => Client::class,
            ]);

        /**
         * @var LaravelFactory $factory
         */
        $factory = $this->app->make(LaravelFactory::class);

        $this->assertEquals(
            Client::class, $this->extractor->extract(
                $factory->schema($schema)
            )
        );
    }

    protected function setUp()
    {
        parent::setUp();

        $this->extractor = $this->app->make(TypeExtractor::class);

        $this->app->instance(Loader::class, new LoaderMock([
            'dal/models/client' => $this->getFixturePath('schema/dal/models/client.json'),
        ]));
    }
}
