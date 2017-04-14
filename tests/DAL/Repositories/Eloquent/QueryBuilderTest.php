<?php

namespace TreeSoft\Tests\Specifications\DAL\Repositories\Eloquent;

use TreeSoft\Specifications\DAL\Eloquent\Model;
use TreeSoft\Specifications\DAL\Eloquent\QueryBuilder;
use TreeSoft\Tests\Specifications\DAL\TestCase;

/**
 * Class QueryBuilderTest.
 */
class QueryBuilderTest extends TestCase
{
    /**
     * @var QueryBuilder
     */
    private $builder;

    /**
     * @dataProvider expressionProvider
     *
     * @param string $sql
     * @param $values
     * @param $expression
     */
    public function testBuild(string $sql, $values, $expression)
    {
        $query = $this->builder->build(new Model(), $expression);

        $bindings = $query->getBindings();

        $this->assertEquals($sql, $query->toSql());
        $this->assertEquals($values, $bindings);
    }

    /**
     * @return array
     */
    public function expressionProvider()
    {
        $expressions = $this->loadEntities('expressions.yml');
        $sqls = $this->loadArray('sqls.yml');

        foreach (['all', 'filter-by-id'] as $test) {
            yield $test => [$sqls[$test]['query'], $sqls[$test]['bindings'], $expressions[$test] ?? null];
        }
    }

    protected function setUp()
    {
        parent::setUp();

        $this->builder = $this->app->make(QueryBuilder::class);
    }
}
