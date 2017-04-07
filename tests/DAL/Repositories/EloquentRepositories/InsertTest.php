<?php

namespace TreeSoft\Tests\Specifications\DAL\Repositories\EloquentRepositories;

use TreeSoft\Specifications\DAL\Eloquent\Model;
use TreeSoft\Specifications\DAL\Eloquent\AbstractRepository;
use TreeSoft\Specifications\DAL\Transformers\EntityTransformer;
use TreeSoft\Specifications\DAL\Transformers\EntityTransformerFactory;
use TreeSoft\Specifications\Support\Testing\CallsTrait;
use TreeSoft\Tests\Specifications\Mocks\DAL\Entities\Client;
use TreeSoft\Tests\Specifications\Mocks\DAL\Models\ModelMock;

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
         * @var AbstractRepository $repository
         */
        $repository = new class($factory) extends AbstractRepository {
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
