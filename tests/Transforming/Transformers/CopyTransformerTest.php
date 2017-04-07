<?php

namespace TreeSoft\Tests\Specifications\Transforming\Transformers;

use TreeSoft\Specifications\Transforming\Transformers\CopyTransformer;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class CopyTransformerTest extends TestCase
{
    /**
     * @var string
     */
    protected $transformerClass = CopyTransformer::class;

    public function testCopy()
    {
        $data = [
            'test' => (object) [
                'test' => [
                    'test2', 'test3',
                ],
            ],
        ];

        $this->assertEquals($data, $this->transformer->transform($data));
    }
}
