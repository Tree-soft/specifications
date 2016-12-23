<?php

namespace Mildberry\Specifications\Generators\PhpDocGenerators;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
trait ObjectTypeTrait
{
    public function convertType($type): string
    {
        $map = [
            'integer' => 'int',
        ];

        return $map[$type] ?? $type;
    }
}
