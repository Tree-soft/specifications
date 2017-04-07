<?php

namespace TreeSoft\Tests\Specifications\Transforming\Transformers;

use TreeSoft\Specifications\Transforming\Transformers\AbstractTransformer;
use TreeSoft\Tests\Specifications\TestCase as ParentTestCase;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class TestCase extends ParentTestCase
{
    /**
     * @var AbstractTransformer
     */
    protected $transformer;

    /**
     * @var string
     */
    protected $transformerClass;

    protected function setUp()
    {
        parent::setUp();

        $this->transformer = $this->app->make($this->transformerClass);
    }
}
