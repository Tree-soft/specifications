<?php

namespace Mildberry\Tests\Specifications\DAL\Repositories\EloquentRepositories;

use Mildberry\Specifications\DAL\EloquentMapper\Model;
use Mildberry\Specifications\DAL\Repositories\AbstractEloquentRepository;
use Mildberry\Specifications\DAL\Transformers\EntityTransformer;
use Mildberry\Specifications\DAL\Transformers\EntityTransformerFactory;
use Mildberry\Tests\Specifications\Mocks\CallsTrait;
use Mildberry\Tests\Specifications\Mocks\DAL\Entities\Client;
use Mildberry\Tests\Specifications\Mocks\DAL\Models\ModelMock;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class InsertTest extends TestCase
{
    public function testInsert()
    {
        /** @noinspection PhpMissingParentConstructorInspection */
        $transformer = new class() extends EntityTransformer {
            use CallsTrait;

            /**
             * @var Client
             */
            public $actual;

            /**
             *  constructor.
             */
            public function __construct()
            {
            }

            /**
             * @param object $entity
             * @param Model $model
             *
             * @return Model
             */
            public function extract($entity, Model $model): Model
            {
                $this->_handle(__FUNCTION__, $entity, $model);

                return $model;
            }

            /**
             * @param Model $model
             * @param null $entity
             *
             * @return Client
             */
            public function populate(Model $model, $entity = null)
            {
                $this->_handle(__FUNCTION__, $model);

                return $this->actual;
            }
        };

        $objects = $this->loadEntities('client.yml');
        $client = $objects['client'];

        $transformer->actual = new Client();

        $this->app->instance(EntityTransformer::class, $transformer);

        $model = new ModelMock('schema://dal/models/client');

        $this->app->instance(ModelMock::class, $model);

        /**
         * @var EntityTransformerFactory $factory
         */
        $factory = $this->app->make(EntityTransformerFactory::class);

        /**
         * @var AbstractEloquentRepository $repository
         */
        $repository = new class($factory) extends AbstractEloquentRepository {
            /**
             * @var string
             */
            protected $model = ModelMock::class;
        };

        $repository
            ->setContainer($this->app);

        /**
         * @var Client $actual
         */
        $this->assertSame($transformer->actual, $repository->insert($client));

        $this->assertEquals([
            [
                'method' => 'extract',
                'args' => [
                    $client, $model,
                ],
            ], [
                'method' => 'populate',
                'args' => [
                    $model,
                ],
            ],
        ], $transformer->calls);

        $this->assertEquals([
            [
                'method' => 'save',
                'args' => [[]],
            ],
        ], $model->calls);
    }
}
