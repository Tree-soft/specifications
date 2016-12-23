<?php

namespace Mildberry\Specifications\Generators;

use Mildberry\Specifications\Exceptions\UnknownTypeException;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class BuilderFactory
{
    /**
     * @var array
     */
    protected $types = [];

    public function create($schema)
    {
        if (empty($schema->type) || $this->types[$schema->type]) {
            $exception = new UnknownTypeException('Unknown type of scheme');
            $exception
                ->setSchema($schema);

            throw $exception;
        }

        $class = $this->types[$schema->type];

        return new $class();
    }
}
