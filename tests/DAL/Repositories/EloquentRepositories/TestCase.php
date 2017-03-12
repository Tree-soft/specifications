<?php

namespace Mildberry\Tests\Specifications\DAL\Repositories\EloquentRepositories;

use Mildberry\Specifications\Schema\LaravelFactory;
use Mildberry\Specifications\Schema\Loader;
use Mildberry\Specifications\Transforming\Converter\Extractor;
use Mildberry\Tests\Specifications\DAL\TestCase as ParentTestCase;

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
     * @param string $namespace
     *
     * @return object
     */
    public function extract($object, $schema, string $namespace = null)
    {
        /**
         * @var LaravelFactory $factory
         */
        $factory = $this->app->make(LaravelFactory::class);

        /**
         * @var Extractor $extractor
         */
        $extractor = $this->app->make(Extractor::class);

        if (isset($namespace)) {
            $extractor
                ->setNamespace($namespace);
        }

        return $extractor->convert($object, $factory->schema($schema));
    }
}
