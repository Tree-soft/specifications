<?php

namespace TreeSoft\Tests\Specifications\DAL\Repositories\Eloquent\Repositories;

use TreeSoft\Tests\Specifications\Mocks\Dal\Models\ModelMock;

/**
 * Class DeleteTest
 */
class DeleteTest extends TestCase
{
    public function testDelete() {
        $objects = $this->loadEntities('client.yml');

        $repository = $this->createRepository();

        $model = new ModelMock('schema://dal/models/client');
        $this->app->instance(ModelMock::class, $model);

        $actual = $objects['client_new'];

        $this->instanceTransformer($actual);

        $repository->delete($actual);

        $this->assertEquals([
            [
                'method' => 'delete',
                'args' => []
            ]
        ], $model->calls);
    }
}