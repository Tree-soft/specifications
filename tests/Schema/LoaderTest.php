<?php

namespace TreeSoft\Tests\Specifications\Schema;

use TreeSoft\Specifications\Schema\Loader;
use TreeSoft\Tests\Specifications\TestCase;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class LoaderTest extends TestCase
{
    /**
     * @var Loader
     */
    private $loader;

    /**
     * @dataProvider pathsProvider
     *
     * @param string $expected
     * @param string $root
     * @param string $path
     */
    public function testFileName(string $expected, string $root, string $path)
    {
        $this->loader->setPath($root);

        $this->assertEquals($expected, $this->loader->getFileName($path));
    }

    /**
     * @return array
     */
    public function pathsProvider()
    {
        return [
            'common/empty' => [
                'common/empty.json', '', 'common/empty',
            ],
            'with root' => [
                __DIR__.'/test.json', __DIR__, 'test',
            ],
        ];
    }

    protected function setUp()
    {
        parent::setUp();

        $this->loader = new Loader();
    }
}
