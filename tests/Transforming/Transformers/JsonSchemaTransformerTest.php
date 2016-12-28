<?php

namespace Mildberry\Tests\Specifications\Transforming\Transformers;

use DeepCopy\DeepCopy;
use Mildberry\Specifications\Schema\Loader;
use Mildberry\Specifications\Transforming\Transformers\JsonSchemaTransformer;
use Mildberry\Tests\Specifications\Mocks\LoaderMock;

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
            ->setTransformation($transformation);

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
            'company' => (object) [
                'id' => 5,
                'name' => 'Company name',
            ],
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
                    'to' => 'schema://entities/client',
                    'from' => 'schema://entities/derived/client',
                    'rules' => (object) [],
                ],
                $client,
            ],
            'ignore' => [
                $client, (object) [
                    'to' => 'schema://entities/derived/client',
                    'from' => 'schema://entities/client',
                    'rules' => (object) [
                        'ext' => 'ignore',
                    ],
                ], $extendedClient,
            ],
            'ignore-save-old' => [
                $extendedClient, (object) [
                    'to' => 'schema://entities/derived/client',
                    'from' => 'schema://entities/client',
                    'rules' => (object) [
                        'ext' => 'ignore',
                    ],
                ], $extendedClient2, $extendedClient,
            ],
            'remove' => [
                $client, (object) [
                    'to' => 'schema://entities/derived/client',
                    'from' => 'schema://entities/client',
                    'rules' => (object) [
                        'ext' => 'remove',
                    ],
                ], $extendedClient2, $extendedClient,
            ],
            'extend' => [
                $extendedClient, (object) [
                    'to' => 'schema://entities/derived/client',
                    'from' => 'schema://entities/client',
                    'rules' => (object) [
                        'ext' => 'const:Ext',
                    ],
                ], $client,
            ],
            'extend-with-old' => [
                $extendedClient, (object) [
                    'to' => 'schema://entities/derived/client',
                    'from' => 'schema://entities/client',
                    'rules' => (object) [
                        'ext' => 'const:Ext',
                    ],
                ], $client, $extendedClient2,
            ],
            'shiftFrom' => [
                $extendedClient3, (object) [
                    'to' => 'schema://entities/derived/client',
                    'from' => 'schema://entities/client',
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

    protected function setUp()
    {
        parent::setUp();

        $this->app->instance(Loader::class, new LoaderMock([
            'entities/company' => $this->getFixturePath('schema/company.json'),
            'entities/client' => $this->getFixturePath('schema/client.json'),
            'entities/derived/client' => $this->getFixturePath('schema/derived/client.json'),
        ]));
    }
}
