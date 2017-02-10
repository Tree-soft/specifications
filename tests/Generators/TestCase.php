<?php

namespace Mildberry\Tests\Specifications\Generators;

use Mildberry\Specifications\Schema\Factory;
use Mildberry\Specifications\Schema\LaravelFactory;
use Mildberry\Specifications\Schema\Loader;
use Mildberry\Tests\Specifications\Mocks\LoaderMock;
use Mildberry\Tests\Specifications\Mocks\OutputMock;
use Mildberry\Tests\Specifications\TestCase as ParentTestCase;
use Mildberry\Specifications\Generators\AbstractGenerator;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class TestCase extends ParentTestCase
{
    /**
     * @var AbstractGenerator
     */
    protected $generator;

    /**
     * @var string
     */
    protected $classGenerator;

    /**
     * @var Factory
     */
    protected $factory;

    /**
     * @var OutputMock
     */
    protected $output;

    protected function setUp()
    {
        assert(is_a($this->classGenerator, AbstractGenerator::class, true));

        parent::setUp();

        $this->output = $this->app->make(OutputMock::class);

        $this->generator = $this->app->make($this->classGenerator);
        $this->generator
            ->setOutput($this->output);

        $this->app->instance(Loader::class, $this->app->make(LoaderMock::class));

        $this->factory = $this->app->make(LaravelFactory::class);
    }
}
