<?php

namespace TreeSoft\Tests\Specifications\DAL\Transformers;

use TreeSoft\Specifications\DAL\Transformers\EntityTransformer;
use TreeSoft\Specifications\Generators\TypeExtractor;
use TreeSoft\Specifications\Schema\Loader;
use TreeSoft\Specifications\Support\Testing\CallsTrait;
use TreeSoft\Specifications\Transforming\TransformerFactory;
use TreeSoft\Specifications\Transforming\Transformers\AbstractTransformer;
use TreeSoft\Tests\Specifications\DAL\Repositories\EloquentRepositories\TestCase;
use TreeSoft\Tests\Specifications\Mocks\DAL\Entities\Client;
use TreeSoft\Tests\Specifications\Mocks\DAL\Models\ModelMock;
use TreeSoft\Tests\Specifications\Mocks\LoaderMock;
use TreeSoft\Tests\Specifications\Mocks\TransformerFactoryMock;
use Illuminate\Contracts\Config\Repository as Config;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class EntityTransformerTest extends TestCase
{
    /**
     * @var TransformerFactoryMock
     */
    private $factory;
    /**
     * @var EntityTransformer
     */
    private $transformer;

    public function testExtract()
    {
        $objects = $this->loadEntities('client.yml');
        $client = $objects['client'];

        $model = new ModelMock('schema://dal/models/client');

        $this->transformer
            ->setClass(Client::class)
            ->setNamespace('\TreeSoft\Tests\Specifications\Mocks');

        $transformer = new class() extends AbstractTransformer {
            /**
             * @var array
             */
            public $data;

            /**
             * @param mixed $from
             * @param mixed|null $to
             *
             * @return mixed
             */
            public function transform($from, $to = null)
            {
                return $this->data;
            }
        };

        $transformer->data = $this->extract($client, 'schema://dal/models/client');

        $this->factory
            ->setFrom($this->transformer->getSchema(get_class($client)))
            ->setTo($model->schema)
            ->setTransformer($transformer);

        $actual = $this->transformer->extract($client, $model);

        $this->assertEquals((array) $transformer->data, $actual->attributesToArray());
    }

    public function testPopulate()
    {
        $objects = $this->loadEntities('client.yml');
        $client = $objects['client'];

        $model = new ModelMock('schema://dal/models/client');

        $attributes = (array) $this->extract($client, 'schema://dal/models/client');
        $attributes['id'] = 1;

        $model->fill($attributes);

        $this->transformer
            ->setClass(Client::class)
            ->setNamespace('\TreeSoft\Tests\Specifications\Mocks');

        $transformer = new class() extends AbstractTransformer {
            use CallsTrait;

            /**
             * @var array
             */
            public $data;

            /**
             * @param mixed $from
             * @param mixed|null $to
             *
             * @return mixed
             */
            public function transform($from, $to = null)
            {
                $this->_handle(__FUNCTION__, $from, $to);

                return $this->data;
            }
        };

        $expected = $this->extract($client, 'schema://dal/entities/client');

        $expected->id = $attributes['id'];

        $transformer->data = (object) $attributes;

        $this->factory
            ->setFrom($model->schema)
            ->setTo($this->transformer->getSchema(get_class($client)))
            ->setTransformer($transformer);

        $actual = $this->transformer->populate($model, $client);

        $this->assertEquals([
            [
                'method' => 'transform',
                'args' => [(object) $model->toArray(), null],
            ],
        ], $transformer->calls);

        $this->assertEquals(
            $expected,
            $this->extract($actual, 'schema://dal/entities/client')
        );
    }

    protected function setUp()
    {
        parent::setUp();

        /**
         * @var Config $config
         */
        $config = $this->app->make(Config::class);

        $config->set('specifications.namespace', '\TreeSoft\Tests\Specifications\Mocks');

        $this->factory = $this->app->make(TransformerFactoryMock::class);

        $this->app->instance(TransformerFactory::class, $this->factory);

        $this->transformer = $this->app->make(EntityTransformer::class);

        $this->app->instance(Loader::class, new LoaderMock([
            'dal/entities/client' => $this->getFixturePath('schema/dal/entities/client.json'),
            'dal/entities/company' => $this->getFixturePath('schema/dal/entities/company.json'),
            'dal/models/client' => $this->getFixturePath('schema/dal/models/client.json'),
        ]));

        /**
         * @var TypeExtractor $extractor
         */
        $extractor = $this->app->make(TypeExtractor::class);

        $extractor
            ->setBindings([
                'schema://dal/models/client' => Client::class,
            ]);

        $this->app->instance(TypeExtractor::class, $extractor);
    }
}
