<?php

namespace TreeSoft\Tests\Specifications\DAL\Repositories\Eloquent\Repositories;

use TreeSoft\Specifications\Schema\LaravelFactory;
use TreeSoft\Specifications\Schema\Loader;
use TreeSoft\Specifications\Transforming\Converter\Extractor;
use TreeSoft\Tests\Specifications\DAL\TestCase as ParentTestCase;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class TestCase extends ParentTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $loader = new Loader();

        $loader->setPath($this->getFixtureFilename('../schema'));

        $this->app->instance(Loader::class, $loader);
    }

    /**
     * @param object $object
     * @param string $schema
     *
     * @return object
     */
    public function extract($object, $schema)
    {
        /**
         * @var LaravelFactory $factory
         */
        $factory = $this->app->make(LaravelFactory::class);

        /**
         * @var Extractor $extractor
         */
        $extractor = $this->app->make(Extractor::class);

        return $extractor->convert($object, $factory->schema($schema));
    }
}
