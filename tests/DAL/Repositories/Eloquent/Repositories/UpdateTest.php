<?php

namespace TreeSoft\Tests\Specifications\DAL\Repositories\Eloquent\Repositories;

use TreeSoft\Tests\Specifications\Mocks\Dal\Models\ModelMock;

/**
 * Class UpdateTest.
 */
class UpdateTest extends TestCase
{
    public function testsUpdate()
    {
        $objects = $this->loadEntities('client.yml');

        $repository = $this->createRepository();

        $model = new ModelMock('schema://dal/models/client');
        $this->app->instance(ModelMock::class, $model);

        $actual = $objects['client_new'];
        /**
         * @var object $transformer
         */
        $this->instanceTransformer($actual);

        $this->assertSame($actual, $repository->update($objects['client']));
    }
}
