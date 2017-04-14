<?php

namespace TreeSoft\Tests\Specifications\DAL\Repositories\Eloquent\Repositories;

use Illuminate\Database\Eloquent\Collection;
use TreeSoft\Specifications\DAL\Eloquent\Model;
use TreeSoft\Specifications\DAL\Eloquent\QueryBuilder;
use TreeSoft\Specifications\DAL\Eloquent\Transformers\EntityTransformerFactory;
use TreeSoft\Tests\Specifications\Mocks\Dal\Entities\Client;
use TreeSoft\Tests\Specifications\Mocks\Dal\Models\ModelMock;

/**
 * Class SelectTest.
 */
class SelectTest extends TestCase
{
    public function testFind()
    {
        $this->instanceTransformer([new Client()]);

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

        $repository = $this->createRepository();

        $entities = $repository->findAll();

        $this->assertNotEmpty($entities);
    }
}
