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
use TreeSoft\Tests\Specifications\Mocks\RequestChecker\EmptyQueryChecker;
use TreeSoft\Tests\Specifications\Mocks\RequestChecker\HeaderTeapotChecker;
use TreeSoft\Tests\Specifications\Mocks\RequestChecker\IntegerIdChecker;
use TreeSoft\Tests\Specifications\Mocks\RequestChecker\RouteTestChecker;
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
                IntegerIdChecker::class,
                (new RequestMock())
                    ->setData($data),
                $data, [[
                    'keyword' => 'type',
                ], [
                    'keyword' => 'required',
                ]],
                DataValidationException::class,
            ],
            'query' => [
                $schemaMap,
                EmptyQueryChecker::class,
                (new RequestMock())
                    ->setQuery($data),
                $data, [[
                    'keyword' => 'additionalProperties',
                ]],
                QueryValidationException::class,
            ],
            'header' => [
                $schemaMap,
                HeaderTeapotChecker::class,
                (new RequestMock())
                    ->setHeaders($headers),
                $headers, [[
                    'keyword' => 'enum',
                ]],
                HeaderValidationException::class,
            ],
            'route' => [
                $schemaMap,
                RouteTestChecker::class,
                (new RequestMock())
                    ->setRoute($data),
                $data, [[
                    'keyword' => 'type',
                ], [
                    'keyword' => 'required',
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
            ];
        }, $errors);

        $this->assertEquals($expected, $actual);
    }
}
