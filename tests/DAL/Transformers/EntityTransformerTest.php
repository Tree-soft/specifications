<?php

namespace Mildberry\Tests\Specifications\DAL\Transformers;

use Mildberry\Specifications\DAL\Transformers\EntityTransformer;
use Mildberry\Specifications\Schema\Loader;
use Mildberry\Specifications\Transforming\TransformerFactory;
use Mildberry\Specifications\Transforming\Transformers\AbstractTransformer;
use Mildberry\Tests\Specifications\DAL\Repositories\EloquentRepositories\TestCase;
use Mildberry\Tests\Specifications\Mocks\CallsTrait;
use Mildberry\Tests\Specifications\Mocks\DAL\Entities\Client;
use Mildberry\Tests\Specifications\Mocks\DAL\Models\ModelMock;
use Mildberry\Tests\Specifications\Mocks\LoaderMock;
use Mildberry\Tests\Specifications\Mocks\TransformerFactoryMock;

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
            ->setNamespace('\Mildberry\Tests\Specifications\Mocks');

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
            ->setNamespace('\Mildberry\Tests\Specifications\Mocks');

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

        $this->factory = $this->app->make(TransformerFactoryMock::class);

        $this->app->instance(TransformerFactory::class, $this->factory);

        $this->transformer = $this->app->make(EntityTransformer::class);

        $this->app->instance(Loader::class, new LoaderMock([
            'dal/entities/client' => $this->getFixturePath('schema/dal/entities/client.json'),
            'dal/entities/company' => $this->getFixturePath('schema/dal/entities/company.json'),
            'dal/models/client' => $this->getFixturePath('schema/dal/models/client.json'),
        ]));
    }
}
