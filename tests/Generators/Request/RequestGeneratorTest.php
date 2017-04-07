<?php

namespace TreeSoft\Tests\Specifications\Generators\Request;

use TreeSoft\Specifications\Generators\Request\RequestGenerator;
use TreeSoft\Tests\Specifications\TestCase;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class RequestGeneratorTest extends TestCase
{
    /**
     * @var RequestGenerator
     */
    private $generator;

    /**
     * @dataProvider templatesProvider
     *
     * @param callable $filler
     * @param string $fixture
     */
    public function testGenerate($filler, string $fixture)
    {
        $filler($this->generator);
        $this->assertEquals(file_get_contents($fixture), $this->generator->generate());
    }

    /**
     * @return array
     */
    public function templatesProvider()
    {
        $path = $this->getFixturePath('Requests');

        return [
            'data' => [
                function (RequestGenerator $generator) {
                    $generator
                            ->setDataSchema('schema://example/requests/client')
                            ->setClass('ExampleRequest')
                            ->setNamespace('TreeSoft\Tests\Specifications\Fixtures\Requests');
                }, "{$path}/ExampleRequest.php",
            ],
            'data+header+same class' => [
                function (RequestGenerator $generator) {
                    $generator
                        ->setDataSchema('schema://example/requests/client')
                        ->setHeaderSchema('schema://example/requests/client2')
                        ->setClass('Request')
                        ->setNamespace('TreeSoft\Tests\Specifications\Fixtures\Requests');
                }, "{$path}/Request.php",
            ],
        ];
    }

    protected function setUp()
    {
        parent::setUp();

        $this->generator = $this->app->make(RequestGenerator::class);
    }
}
