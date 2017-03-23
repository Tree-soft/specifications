<?php

namespace Mildberry\Tests\Specifications\Transforming\Transformers;

use DeepCopy\DeepCopy;
use Mildberry\Specifications\Exceptions\TransformationNotFoundException;
use Mildberry\Specifications\Schema\Loader;
use Mildberry\Specifications\Support\DataPreparator;
use Mildberry\Specifications\Transforming\TransformerFactory;
use Mildberry\Specifications\Transforming\Transformers\AbstractTransformer;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rule;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Transformations\ConstTransformation;
use Mildberry\Specifications\Transforming\Transformers\JsonSchemaTransformer;
use Mildberry\Tests\Specifications\Mocks\LoaderMock;
use Mildberry\Tests\Specifications\Support\SuccessException;

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
            ->setSpecification($transformation);

        $this->assertEquals($expected, $this->transformer->transform($from, $to));
    }

    /**
     * @return array
     */
    public function objectsProvider(): array
    {
        $preparator = new DataPreparator();

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
        unset($extendedClient3->name);

        $extendedClient4 = $copier->copy($client);
        $extendedClient4->ext = $extendedClient4->name;

        return [
            'simple' => [
                1, $preparator->prepare([
                    'from' => 'schema://types/int',
                    'to' => 'schema://types/int',
                ]), 1,
            ],
            'object' => [
                $client, (object) [
                    'to' => 'schema://entities/simple-client',
                    'from' => 'schema://entities/derived/simple-client',
                    'rules' => (object) [],
                ],
                $client,
            ],
            'ignore' => [
                $client, $preparator->prepare([
                    'from' => 'schema://entities/derived/simple-client',
                    'to' => 'schema://entities/simple-client',
                    'rules' => [
                        'ext>',
                    ],
                ]), $extendedClient,
            ],
            'ignore-save-old' => [
                $extendedClient, $preparator->prepare([
                    'from' => 'schema://entities/derived/simple-client',
                    'to' => 'schema://entities/simple-client',
                    'rules' => [
                        'ext>',
                    ],
                ]), $extendedClient2, $extendedClient,
            ],
            'extend' => [
                $extendedClient, $preparator->prepare([
                    'from' => 'schema://entities/simple-client',
                    'to' => 'schema://entities/derived/simple-client',
                    'rules' => [
                        '>const:Ext>ext',
                    ],
                ]), $client,
            ],
            'extend-with-old' => [
                $extendedClient2, $preparator->prepare([
                    'from' => 'schema://entities/simple-client',
                    'to' => 'schema://entities/derived/simple-client',
                    'rules' => [
                        '>const:Ext>ext',
                    ],
                ]), $client, $extendedClient2,
            ],
            'shift' => [
                $extendedClient3, $preparator->prepare([
                    'to' => 'schema://entities/derived/simple-client',
                    'from' => 'schema://entities/simple-client',
                    'rules' => (object) [
                        'name>ext',
                    ],
                ]), $client,
            ],
            'shift-n-copy' => [
                $extendedClient4, $preparator->prepare([
                    'to' => 'schema://entities/derived/simple-client',
                    'from' => 'schema://entities/simple-client',
                    'rules' => (object) [
                        'name>ext',
                        'name>name',
                    ],
                ]), $client,
            ],
        ];
    }

    public function testNested()
    {
        $container = $this->app;

        $factory = new class($container) extends TransformerFactory {
            /**
             * @param string $from
             * @param string $to
             *
             * @throws SuccessException
             *
             * @return AbstractTransformer
             */
            public function create($from, $to): AbstractTransformer
            {
                throw new SuccessException();
            }
        };

        $this->app->instance(TransformerFactory::class, $factory);

        $transformation = (object) [
            'from' => 'schema://entities/client',
            'to' => 'schema://entities/ext-client',
            'rules' => (object) [],
        ];

        $this->transformer
            ->setSpecification($transformation);

        $from
            = (object) [
            'name' => 'Name',
            'phone' => 'Phone',
            'company' => [
                'name' => 'Company name',
            ],
        ];

        try {
            $this->transformer->transform($from);

            $this->fail('Factory should be called.');
        } catch (SuccessException $e) {
        }
    }

    /**
     * @dataProvider rulesDefinitionsProvider
     *
     * @param Rule $expected
     * @param string $definition
     */
    public function testParseRule(Rule $expected, string $definition)
    {
        foreach ($expected->getTransformations() as $transformation) {
            $transformation->setContainer($this->app);
        }

        $this->assertEquals($expected, $this->transformer->parseRule($definition));
    }

    /**
     * @return array
     */
    public function rulesDefinitionsProvider()
    {
        $data = $this->loadEntities('rules-definitions.yml');

        return [
            'copy' => [$data['copy'], 'ext>ext2'],
            'const' => [$data['const'], '>const:expr1>ext'],
            'ignore' => [$data['ignore'], 'ext>'],
            'general' => [$data['general'], 'ext>const:expr1|const:expr2|const:expr3>ext2'],
        ];
    }

    public function testRegisterTransformation()
    {
        $this->transformer->registerTransformation('copy', ConstTransformation::class);

        $this->assertInstanceOf(ConstTransformation::class, $this->transformer->getTransformation('copy'));
    }

    public function testUnregisterTransformation()
    {
        $this->expectException(TransformationNotFoundException::class);
        $this->expectExceptionMessage("Transformation 'const' not found");

        $this->transformer->removeTransformation('const');
        $this->transformer->getTransformation('const');
    }

    protected function setUp()
    {
        parent::setUp();

        $this->app->instance(Loader::class, new LoaderMock([
            'entities/company' => $this->getFixturePath('schema/company.json'),
            'entities/client' => $this->getFixturePath('schema/client.json'),
            'entities/ext-client' => $this->getFixturePath('schema/ext-client.json'),
            'entities/simple-client' => $this->getFixturePath('schema/simple-client.json'),
            'entities/derived/simple-client' => $this->getFixturePath('schema/derived/simple-client.json'),
            'entities/derived/company' => $this->getFixturePath('schema/derived/company.json'),
            'entities/derived/client' => $this->getFixturePath('schema/derived/client.json'),
            'types/int' => $this->getFixturePath('schema/types/int.json'),
        ]));
    }
}
