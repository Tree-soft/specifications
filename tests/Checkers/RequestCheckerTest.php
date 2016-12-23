<?php

namespace Mildberry\Tests\Specifications\Checkers;

use Mildberry\Specifications\Exceptions\DataValidationException;
use Mildberry\Specifications\Exceptions\HeaderValidationException;
use Mildberry\Specifications\Exceptions\QueryValidationException;
use Mildberry\Specifications\Exceptions\RouteValidationException;
use Mildberry\Specifications\Objects\RequestInterface;
use Mildberry\Specifications\Checkers\Request\RequestChecker;
use Mildberry\Tests\Specifications\Mocks\LoaderMock;
use Mildberry\Tests\Specifications\Mocks\RequestMock;
use Mildberry\Tests\Specifications\Mocks\Specifications\EmptyQuerySpecification;
use Mildberry\Tests\Specifications\Mocks\Specifications\HeaderTeapotSpecification;
use Mildberry\Tests\Specifications\Mocks\Specifications\IntegerIdSpecification;
use Mildberry\Tests\Specifications\Mocks\Specifications\RouteTestSpecification;
use Mildberry\Tests\Specifications\TestCase;
use Mildberry\Specifications\Schema\Loader;
use Mildberry\Specifications\Exceptions\EntityValidationException;
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
     * @param array $data
     * @param array $expected
     * @param string $exceptionClass
     */
    public function testWrongSpecification(
        array $schemaMap, string $class, RequestInterface $request,
        array $data, array $expected, string $exceptionClass = null
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
