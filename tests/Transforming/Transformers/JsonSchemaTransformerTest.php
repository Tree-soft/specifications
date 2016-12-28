<?php

namespace Mildberry\Tests\Specifications\Transforming\Transformers;

use DeepCopy\DeepCopy;
use Mildberry\Specifications\Schema\Loader;
use Mildberry\Specifications\Transforming\Transformers\JsonSchemaTransformer;
use Mildberry\Tests\Specifications\Mocks\LoaderMock;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class JsonSchemaTransformerTest extends TestCase
{
    /**
     * @var JsonSchemaTransformer
     */
    protected $transformer;
    /**
     * @var string
     */
    protected $transformerClass = JsonSchemaTransformer::class;

    /**
     * @dataProvider objectsProvider
     *
     * @param mixed $expected
     * @param object $transformation
     * @param mixed $from
     * @param mixed $to
     */
    public function testTransform($expected, $transformation, $from, $to = null)
    {
        $this->transformer
            ->setTransformation($transformation)
            ->setRules([
                'ignore' => Rules\IgnoreRule::class,
                'remove' => Rules\RemoveRule::class,
                'const' => Rules\ConstRule::class,
                'shiftFrom' => Rules\ShiftFromRule::class,
                'shiftTo' => Rules\ShiftToRule::class,
            ]);

        $this->assertEquals($expected, $this->transformer->transform($from, $to));
    }

    /**
     * @return array
     */
    public function objectsProvider(): array
    {
        $copier = new DeepCopy();

        $client = (object) [
            'name' => 'Name',
            'phone' => 'Phone',
        ];

        $extendedClient = $copier->copy($client);
        $extendedClient->ext = 'Ext';

        $extendedClient2 = $copier->copy($client);
        $extendedClient2->ext = 'Ext2';

        $extendedClient3 = $copier->copy($client);
        $extendedClient3->ext = $extendedClient3->name;

        return [
            'simple' => [
                $client, (object) [
                    'to' => 'schema://entities/simple-client',
                    'from' => 'schema://entities/derived/simple-client',
                    'rules' => (object) [],
                ],
                $client,
            ],
            'ignore' => [
                $client, (object) [
                    'to' => 'schema://entities/derived/simple-client',
                    'from' => 'schema://entities/simple-client',
                    'rules' => (object) [
                        'ext' => 'ignore',
                    ],
                ], $extendedClient,
            ],
            'ignore-save-old' => [
                $extendedClient, (object) [
                    'to' => 'schema://entities/derived/simple-client',
                    'from' => 'schema://entities/simple-client',
                    'rules' => (object) [
                        'ext' => 'ignore',
                    ],
                ], $extendedClient2, $extendedClient,
            ],
            'remove' => [
                $client, (object) [
                    'to' => 'schema://entities/derived/simple-client',
                    'from' => 'schema://entities/simple-client',
                    'rules' => (object) [
                        'ext' => 'remove',
                    ],
                ], $extendedClient2, $extendedClient,
            ],
            'extend' => [
                $extendedClient, (object) [
                    'to' => 'schema://entities/derived/simple-client',
                    'from' => 'schema://entities/simple-client',
                    'rules' => (object) [
                        'ext' => 'const:Ext',
                    ],
                ], $client,
            ],
            'extend-with-old' => [
                $extendedClient, (object) [
                    'to' => 'schema://entities/derived/simple-client',
                    'from' => 'schema://entities/simple-client',
                    'rules' => (object) [
                        'ext' => 'const:Ext',
                    ],
                ], $client, $extendedClient2,
            ],
            'shiftFrom' => [
                $extendedClient3, (object) [
                    'to' => 'schema://entities/derived/simple-client',
                    'from' => 'schema://entities/simple-client',
                    'rules' => (object) [
                        'ext' => 'shiftFrom:name',
                    ],
                ], $client,
            ],
//            'shiftTo' => [
//                $extendedClient3, (object) [
//                    'to' => 'schema://entities/derived/client',
//                    'from' => 'schema://entities/client',
//                    'rules' => (object) [
//                        'name' => 'shiftTo:ext',
//                    ],
//                ], $client
//            ]
        ];
    }

//    public function testNested() {
//        $factory = new class() extends TransformerFactory {
//            /**
//             * @param string $from
//             * @param string $to
//             * @return AbstractTransformer
//             * @throws SuccessException
//             */
//            public function create(string $from, string $to): AbstractTransformer
//            {
//                throw new SuccessException();
//            }
//        };
//
//        $transformation = (object) [
//            'from' => 'schema://entities/client',
//            'to' => 'schema://entities/ext-client',
//            'rules' => (object) [],
//        ];
//
//        $this->transformer
//            ->setTransformation($transformation)
//            ->setRules([
//                'ignore' => Rules\IgnoreRule::class,
//                'remove' => Rules\RemoveRule::class,
//                'const' => Rules\ConstRule::class,
//                'shiftFrom' => Rules\ShiftFromRule::class,
//                'shiftTo' => Rules\ShiftToRule::class,
//            ]);
//
//        $this->assertEquals($expected, $this->transformer->transform($from, $to));
//
//        $this->app->instance(TransformerFactory::class, $factory);
//    }

    protected function setUp()
    {
        parent::setUp();

        $this->app->instance(Loader::class, new LoaderMock([
//            'entities/company' => $this->getFixturePath('schema/company.json'),
            'entities/simple-client' => $this->getFixturePath('schema/simple-client.json'),
            'entities/derived/simple-client' => $this->getFixturePath('schema/derived/simple-client.json'),
        ]));
    }
}
