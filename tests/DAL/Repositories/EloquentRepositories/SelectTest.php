<?php

namespace TreeSoft\Tests\Specifications\DAL\Repositories\EloquentRepositories;

use Illuminate\Database\Eloquent\Collection;
use TreeSoft\Specifications\DAL\Eloquent\AbstractRepository;
use TreeSoft\Specifications\DAL\Eloquent\Model;
use TreeSoft\Specifications\DAL\Eloquent\QueryBuilder;
use TreeSoft\Specifications\DAL\Eloquent\Transformers\EntityTransformer;
use TreeSoft\Specifications\DAL\Eloquent\Transformers\EntityTransformerFactory;
use TreeSoft\Specifications\Support\Testing\CallsTrait;
use TreeSoft\Tests\Specifications\Mocks\Dal\Entities\Client;
use TreeSoft\Tests\Specifications\Mocks\Dal\Models\ModelMock;

/**
 * Class SelectTest.
 */
class SelectTest extends TestCase
{
    public function testFind()
    {
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

        $transformer->actual = [new Client()];

        $this->app->instance(EntityTransformer::class, $transformer);

        $model = new ModelMock('schema://dal/models/client');
        $this->app->instance(ModelMock::class, $model);

        $this->app->instance(QueryBuilder::class, new class() extends QueryBuilder {
            /**
             * @param Model $model
             * @param $expression
             *
             * @return Collection
             */
            public function result(Model $model, $expression): Collection
            {
                return new Collection([$model]);
            }
        });

        /**
         * @var EntityTransformerFactory $factory
         */
        $factory = $this->app->make(EntityTransformerFactory::class);

        $factory
            ->setBuilders([
                ModelMock::class => Client::class,
            ]);

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

        $expression = [];

        $entities = $repository->findBy($expression);

        $this->assertNotEmpty($entities);
    }
}
