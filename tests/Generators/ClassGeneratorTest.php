<?php

namespace Mildberry\Tests\Specifications\Generators;

use Mildberry\Specifications\Generators\ClassBuilders\TypeExtractor;
use Mildberry\Specifications\Generators\ClassGenerator;
use Mildberry\Specifications\Schema\Factory;
use Mildberry\Specifications\Schema\LaravelFactory;
use Mildberry\Specifications\Schema\Loader;
use Mildberry\Tests\Specifications\Mocks\LoaderMock;
use Mildberry\Tests\Specifications\Mocks\OutputMock;
use Mildberry\Tests\Specifications\TestCase;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ClassGeneratorTest extends TestCase
{
    /**
     * @var ClassGenerator
     */
    private $generator;

    /**
     * @var Factory
     */
    private $factory;

    /**
     * @var OutputMock
     */
    private $output;

    public function testSaveOneFile()
    {
        $schema = $this->factory->schema('schema://entities/company');

        $this->generator->getExtractor()->setNamespace('\Mildberry\Tests\Specifications\Fixtures');
        $this->generator->generate($schema);

        $this->assertEquals([
            'Entities/Company.php' => file_get_contents($this->getFixturePath('entities/Company.php')),
        ], $this->output->getFiles());
    }

    /**
     * @dataProvider fileNamesProvider
     *
     * @param string $expected
     * @param object $schema
     */
    public function testGetFilename(string $expected, $schema)
    {
        $this->assertEquals($expected, $this->generator->getFilename($schema));
    }

    /**
     * @return array
     */
    public function fileNamesProvider()
    {
        return [
            'simple' => [
                'Mock/Company.php', (object) [
                    'id' => 'schema://mock/company',
                    'type' => 'object',
                ],
            ],
            'hyphen' => [
                'Mock/HyphenClass.php', (object) [
                    'id' => 'schema://mock/hyphen-class',
                    'type' => 'object',
                ],
            ],
            'class' => [
                'TestNamespace/TestClass.php', (object) [
                    'id' => 'schema://mock/company',
                    'classGenerator' => (object) [
                        'class' => 'TestNamespace\TestClass',
                    ],
                    'type' => 'object',
                ],
            ],
            'fileName' => [
                'Test/Test.php', (object) [
                    'id' => 'schema://mock/company',
                    'classGenerator' => (object) [
                        'filename' => 'Test/Test.php',
                    ],
                    'type' => 'object',
                ],
            ],
        ];
    }

    protected function setUp()
    {
        parent::setUp();

        $this->output = $this->app->make(OutputMock::class);

        $this->generator = $this->app->make(ClassGenerator::class);
        $this->generator
            ->setOutput($this->output)
            ->setExtractor(new TypeExtractor());

        $this->app->instance(Loader::class, new LoaderMock([
            'entities/company' => $this->getFixturePath('schema/company.json'),
        ]));

        $this->factory = $this->app->make(LaravelFactory::class);
    }
}
