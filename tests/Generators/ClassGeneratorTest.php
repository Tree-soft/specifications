<?php

namespace Mildberry\Tests\Specifications\Generators;

use Mildberry\Specifications\Generators\ClassBuilders\TypeExtractor;
use Mildberry\Specifications\Generators\ClassGenerator;
use Mildberry\Specifications\Schema\LaravelFactory;
use Mildberry\Specifications\Schema\Loader;
use Mildberry\Tests\Specifications\Mocks\LoaderMock;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ClassGeneratorTest extends TestCase
{
    /**
     * @var ClassGenerator
     */
    protected $generator;

    /**
     * @var string
     */
    protected $classGenerator = ClassGenerator::class;

    /**
     * @dataProvider filesProvider
     *
     * @param array $expected
     * @param string $fnSchema
     */
    public function testGenerate(array $expected, string $fnSchema)
    {
        $schema = $this->factory->schema($fnSchema);

        $this->generator->getExtractor()->setNamespace('\Mildberry\Tests\Specifications\Fixtures');
        $this->generator->generate($schema);

        $this->assertEquals($expected, $this->output->getFiles());
    }

    /**
     * @return array
     */
    public function filesProvider(): array
    {
        return [
            'one-file' => [
                [
                    'Entities/Company.php' => file_get_contents($this->getFixturePath('Entities/Company.php')),
                ], 'schema://entities/company',
            ],
            'class' => [
                [
                    'Entities/ClientFull.php' => file_get_contents($this->getFixturePath('Entities/ClientFull.php')),
                ], 'schema://entities/clientFull',
            ],
            'derived-class' => [
                [
                    'Entities/Derived/Client.php' =>
                        file_get_contents($this->getFixturePath('Entities/Derived/Client.php')),
                ], 'schema://entities/derived/client',
            ],
            'nullable' => [
                [
                    'Entities/Nullable.php' =>
                        file_get_contents($this->getFixturePath('Entities/Nullable.php')),
                ], 'schema://entities/nullable',
            ],
            'underscore' => [
                [
                    'Entities/UnderscoreClient.php' =>
                        file_get_contents($this->getFixturePath('Entities/UnderscoreClient.php')),
                ], 'schema://entities/underscore-client',
            ],
        ];
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

        $this->generator
            ->setExtractor($this->app->make(TypeExtractor::class));

        $this->app->instance(Loader::class, new LoaderMock([
            'entities/company' => $this->getFixturePath('schema/company.json'),
            'entities/client' => $this->getFixturePath('schema/client.json'),
            'entities/clientFull' => $this->getFixturePath('schema/clientFull.json'),
            'entities/derived/client' => $this->getFixturePath('schema/derived/client.json'),
            'entities/nullable' => $this->getFixturePath('schema/nullable.json'),
            'common/id' => $this->getResourcePath('schema/common/id.json'),
            'entities/underscore-client' => $this->getFixturePath('schema/underscore/client.json'),
            'entities/underscore-company' => $this->getFixturePath('schema/underscore/company.json'),
        ]));

        $this->factory = $this->app->make(LaravelFactory::class);
    }
}
