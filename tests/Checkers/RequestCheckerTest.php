<?php

namespace TreeSoft\Tests\Specifications\Checkers;

use TreeSoft\Specifications\Exceptions\DataValidationException;
use TreeSoft\Specifications\Exceptions\HeaderValidationException;
use TreeSoft\Specifications\Exceptions\QueryValidationException;
use TreeSoft\Specifications\Exceptions\RouteValidationException;
use TreeSoft\Specifications\Objects\RequestInterface;
use TreeSoft\Specifications\Checkers\Request\RequestChecker;
use TreeSoft\Specifications\Support\DataPreparator;
use TreeSoft\Tests\Specifications\Mocks\LoaderMock;
use TreeSoft\Tests\Specifications\Mocks\RequestMock;
use TreeSoft\Tests\Specifications\Mocks\Specifications\EmptyQuerySpecification;
use TreeSoft\Tests\Specifications\Mocks\Specifications\HeaderTeapotSpecification;
use TreeSoft\Tests\Specifications\Mocks\Specifications\IntegerIdSpecification;
use TreeSoft\Tests\Specifications\Mocks\Specifications\RouteTestSpecification;
use TreeSoft\Tests\Specifications\TestCase;
use TreeSoft\Specifications\Schema\Loader;
use TreeSoft\Specifications\Exceptions\EntityValidationException;
use League\JsonGuard\ValidationError;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class RequestCheckerTest extends TestCase
{
    public function testEmpty()
    {
        $specification = $this->app->make(RequestChecker::class);

        $specification->check(new RequestMock());
    }

    /**
     * @dataProvider specificationsProvider
     *
     * @param array $schemaMap
     * @param string $class
     * @param RequestInterface $request
     * @param array $expectedData
     * @param array $expected
     * @param string $exceptionClass
     */
    public function testWrongSpecification(
        array $schemaMap, string $class, RequestInterface $request,
        $expectedData, array $expected, string $exceptionClass = null
    ) {
        $this->app->instance(Loader::class, new LoaderMock($schemaMap));

        /**
         * @var RequestChecker $specification
         */
        $specification = $this->app->make($class);

        try {
            $specification->check($request);
            $this->fail('Request should be failed');
        } catch (EntityValidationException $e) {
            if (isset($exceptionClass)) {
                $this->assertInstanceOf($exceptionClass, $e);
            }

            $this->assertEquals($expectedData, $e->getData());
            $this->assertValidationsError($expected, $e->getErrors());
        }
    }

    /**
     * @return array
     */
    public function specificationsProvider()
    {
        $preparator = new DataPreparator();

        $data = $preparator->prepare([
            'id' => 'test',
        ]);

        $headers = $preparator->prepare([
            'X-TEAPOT' => 'Bosch XXX',
        ]);

        $fixture = $this->getFixturePath('schema');
        $resource = $this->getResourcePath('schema');

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
                DataValidationException::class,
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
                QueryValidationException::class,
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
                HeaderValidationException::class,
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
                RouteValidationException::class,
            ],
        ];
    }

    /**
     * @param $expected
     * @param $errors
     */
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
