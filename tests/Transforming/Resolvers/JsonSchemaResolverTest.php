<?php

namespace TreeSoft\Tests\Specifications\Transforming\Resolvers;

use TreeSoft\Specifications\Support\DataPreparator;
use TreeSoft\Specifications\Transforming\Resolvers\JsonSchemaResolver;
use TreeSoft\Tests\Specifications\Core\TestCase;
use Generator;

/**
 * Class JsonSchemaResolverTest.
 */
class JsonSchemaResolverTest extends TestCase
{
    /**
     * @var JsonSchemaResolver
     */
    private $resolver;

    public function testResolve()
    {
        $preparator = new DataPreparator();

        $this->resolver->setConfig([
            'schema' => $preparator->prepare([
                'transformations' => [
                    'test' => [
                        'from' => 'schema://common/id',
                        'to' => 'schema://common/datetime',
                    ],
                ],
            ]),
        ]);

        $this->resolver->resolve((object) [
            'id' => 'schema://common/id',
        ], 'schema://common/datetime', function () {
            $this->fail('Callback must not be called.');
        });
    }

    /**
     * @dataProvider schemaProvider
     *
     * @param mixed $from
     * @param mixed $to
     */
    public function testIsEqual($from, $to)
    {
        $this->assertTrue($this->resolver->isEqual($from, $to));
    }

    /**
     * @return Generator
     */
    public function schemaProvider()
    {
        $schemas = [
            'S' => 'schema://common/id',
            'O' => (object) ['id' => 'schema://common/id'],
        ];

        foreach ($schemas as $lk => $lhs) {
            foreach ($schemas as $rk => $rhs) {
                yield "{$lk}->{$rk}" => [$lhs, $rhs];
            }
        }
    }

    protected function setUp()
    {
        parent::setUp();

        $this->resolver = $this->app->make(JsonSchemaResolver::class);
    }
}
