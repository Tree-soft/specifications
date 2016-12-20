<?php

namespace Mildberry\Specifications\Support;

/**
 * @author Sergei Melnikov <me@rnr.name>
 *
 * @property string|object schema
 */
trait DynamicSchemaInjectorTrait
{
    public function __construct($schema)
    {
        $this->schema = $schema;
    }
}
