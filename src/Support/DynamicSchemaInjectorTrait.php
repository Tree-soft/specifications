<?php

namespace TreeSoft\Specifications\Support;

/**
 * @author Sergei Melnikov <me@rnr.name>
 *
 * @property string|object schema
 */
trait DynamicSchemaInjectorTrait
{
    /**
     * @param string|object $schema
     *
     * @return $this
     */
    public function setSchema($schema)
    {
        $this->schema = $schema;

        return $this;
    }
}
