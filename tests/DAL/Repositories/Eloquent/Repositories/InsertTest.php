<?php

namespace TreeSoft\Tests\Specifications\DAL\Repositories\Eloquent\Repositories;

use TreeSoft\Tests\Specifications\Mocks\Dal\Entities\Client;
use TreeSoft\Tests\Specifications\Mocks\Dal\Models\ModelMock;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class InsertTest extends TestCase
{
    public function testInsert()
    {
        $objects = $this->loadEntities('client.yml');
        $client = $objects['client'];

        $actual = new Client();

        /**
         * @var object $transformer
         */
        $transformer = $this->instanceTransformer($actual);

        $model = new ModelMock('schema://dal/models/client');
        $this->app->instance(ModelMock::class, $model);

        $repository = $this->createRepository();

        $this->assertSame($actual, $repository->insert($client));

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
