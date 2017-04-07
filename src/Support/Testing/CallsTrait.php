<?php

namespace TreeSoft\Specifications\Support\Testing;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
trait CallsTrait
{
    /**
     * @var array
     */
    public $calls = [];

    /**
     * @param $method
     * @param array ...$args
     */
    protected function _handle($method, ...$args)
    {
        $this->calls[] = [
            'method' => $method,
            'args' => $args,
        ];
    }
}
