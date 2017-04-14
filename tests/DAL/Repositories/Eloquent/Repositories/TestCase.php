<?php

namespace TreeSoft\Tests\Specifications\DAL\Repositories\Eloquent\Repositories;

use TreeSoft\Specifications\DAL\Eloquent\AbstractRepository;
use TreeSoft\Specifications\DAL\Eloquent\Model;
use TreeSoft\Specifications\DAL\Eloquent\Transformers\EntityTransformer;
use TreeSoft\Specifications\DAL\Eloquent\Transformers\EntityTransformerFactory;
use TreeSoft\Specifications\Schema\LaravelFactory;
use TreeSoft\Specifications\Schema\Loader;
use TreeSoft\Specifications\Support\Testing\CallsTrait;
use TreeSoft\Specifications\Transforming\Converter\Extractor;
use TreeSoft\Tests\Specifications\DAL\TestCase as ParentTestCase;
use TreeSoft\Tests\Specifications\Mocks\Dal\Entities\Client;
use TreeSoft\Tests\Specifications\Mocks\Dal\Models\ModelMock;

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

        /**
         * @var EntityTransformerFactory $factory
         */
        $factory = $this->app->make(EntityTransformerFactory::class);

        $factory
            ->setBuilders([
                ModelMock::class => Client::class,
            ]);

        $this->app->instance(EntityTransformerFactory::class, $factory);
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

    /**
     * @return AbstractRepository
     */
    protected function createRepository(): AbstractRepository
    {
        /**
         * @var EntityTransformerFactory $factory
         */
        $factory = $this->app->make(EntityTransformerFactory::class);

        /**
         * @var AbstractRepository $repository
         */
        $repository = new class($factory) extends AbstractRepository {
            /**
             * @var string
             */
            protected $model = ModelMock::class;
        };

        $repository
            ->setContainer($this->app);

        return $repository;
    }

    /**
     * @param mixed $actual
     *
     * @return EntityTransformer
     */
    protected function instanceTransformer($actual): EntityTransformer
    {
        $transformer = new class() extends EntityTransformer {
            use CallsTrait;

            /**
             * @var Client
             */
            public $actual;

            /**
             *  constructor.
             */
            public function __construct()
            {
            }

            /**
             * @param object $entity
             * @param Model $model
             *
             * @return Model
             */
            public function extract($entity, Model $model): Model
            {
                $this->_handle(__FUNCTION__, $entity, $model);

                return $model;
            }

            /**
             * @param Model $model
             * @param null $entity
             *
             * @return Client
             */
            public function populate(Model $model, $entity = null)
            {
                $this->_handle(__FUNCTION__, $model);

                return $this->actual;
            }
        };

        $transformer->actual = $actual;
        $this->app->instance(EntityTransformer::class, $transformer);

        return $transformer;
    }
}
