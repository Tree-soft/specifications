<?php

namespace Mildberry\Tests\Specifications\Specifications;

use Mildberry\Specifications\Objects\RequestInterface;
use Mildberry\Specifications\Specifications\Request\RequestSpecification;
use Mildberry\Tests\Specifications\Mocks\LoaderMock;
use Mildberry\Tests\Specifications\Mocks\RequestMock;
use Mildberry\Tests\Specifications\Mocks\Specifications\EmptyQuerySpecification;
use Mildberry\Tests\Specifications\Mocks\Specifications\HeaderTeapotSpecification;
use Mildberry\Tests\Specifications\Mocks\Specifications\IntegerIdSpecification;
use Mildberry\Tests\Specifications\Mocks\Specifications\RouteTestSpecification;
use Mildberry\Tests\Specifications\TestCase;
use Mildberry\Specifications\Schema\Loader;
use Mildberry\Specifications\Exceptions\EntityValidateException;
use League\JsonGuard\ValidationError;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class RequestSpecificationTest extends TestCase
{
    public function testEmpty()
    {
        $specification = $this->app->make(RequestSpecification::class);

        $specification->check(new RequestMock());
    }

    /**
     * @dataProvider specificationsProvider
     *
     * @param array $schemaMap
     * @param string $class
     * @param RequestInterface $request
     * @param array $data
     * @param array $expected
     */
    public function testWrongSpecification(
        array $schemaMap, string $class, RequestInterface $request,
        array $data, array $expected
    ) {
        $this->app->instance(Loader::class, new LoaderMock($schemaMap));

        /**
         * @var RequestSpecification $specification
         */
        $specification = $this->app->make($class);

        try {
            $specification->check($request);
            $this->fail('Request should be failed');
        } catch (EntityValidateException $e) {
            $this->assertEquals($data, $e->getData());
            $this->assertValidationsError($expected, $e->getErrors());
        }
    }

    public function specificationsProvider()
    {
        $data = [
            'id' => 'test',
        ];

        $headers = [
            'X-TEAPOT' => 'Bosch XXX',
        ];

        $fixture = dirname(__DIR__) . '/fixtures/scheme';
        $resource = dirname(dirname(__DIR__)) . '/resources/schema';

        $schemaMap = [
            'common/force-empty' => "{$resource}/common/force-empty.json",
            'mock/integer-check' => "{$fixture}/integer-check.json",
            'common/empty' => "{$resource}/common/empty.json",
            'mock/teapot' => "{$fixture}/teapot.json",
        ];

        return [
            'data' => [
                $schemaMap,
                IntegerIdSpecification::class,
                (new RequestMock())
                    ->setData($data),
                $data, [[
                    'keyword' => 'type',
                    'message' => 'Value "test" is not a(n) "integer"',
                ], [
                    'keyword' => 'required',
                    'message' => 'Required properties missing: ["name"]',
                ]],
            ],
            'query' => [
                $schemaMap,
                EmptyQuerySpecification::class,
                (new RequestMock())
                    ->setQuery($data),
                $data, [[
                    'keyword' => 'additionalProperties',
                    'message' => 'Additional properties found which are not allowed: "id"',
                ]],
            ],
            'header' => [
                $schemaMap,
                HeaderTeapotSpecification::class,
                (new RequestMock())
                    ->setHeaders($headers),
                $headers, [[
                    'keyword' => 'enum',
                    'message' => 'Value "Bosch XXX" is not one of: ["Bosch 123","Vitek"]',
                ]],
            ],
            'route' => [
                $schemaMap,
                RouteTestSpecification::class,
                (new RequestMock())
                    ->setRoute($data),
                $data, [[
                    'keyword' => 'type',
                    'message' => 'Value "test" is not a(n) "integer"',
                ], [
                    'keyword' => 'required',
                    'message' => 'Required properties missing: ["name"]',
                ]],
            ],
        ];
    }

    public function assertValidationsError($expected, $errors)
    {
        $actual = array_map(function (ValidationError $error) {
            return [
                'keyword' => $error->getKeyword(),
                'message' => $error->getMessage(),
            ];
        }, $errors);

        $this->assertEquals($expected, $actual);
    }
}
