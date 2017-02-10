<?php

namespace Mildberry\Tests\Specifications\Support;

use Mildberry\Specifications\Support\Transformers\EntityTransformer;
use Mildberry\Tests\Specifications\TestCase;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class EntityTransformerTest extends TestCase
{
    /**
     * @dataProvider succeedProvider
     *
     * @param string $text
     */
    public function testIsUpperSuccess(string $text)
    {
        /**
         * @var EntityTransformer $transformer
         */
        $transformer = $this->app->make(EntityTransformer::class);

        $this->assertTrue($transformer->isUpperString($text), '');
    }

    /**
     * @return array
     */
    public function succeedProvider()
    {
        return [
            'simple' => ['ABC'],
            'with digit' => ['ABC123'],
        ];
    }

    /**
     * @dataProvider failedProvider
     *
     * @param string $text
     */
    public function testIsUpperFail(string $text)
    {
        /**
         * @var EntityTransformer $transformer
         */
        $transformer = $this->app->make(EntityTransformer::class);

        $this->assertFalse($transformer->isUpperString($text), '');
    }

    /**
     * @return array
     */
    public function failedProvider()
    {
        return [
            'simple' => ['abc123'],
            'with digit' => ['abc123'],
            'only digit' => ['123'],
        ];
    }
}
