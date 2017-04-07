<?php

namespace TreeSoft\Specifications\Generators\ClassBuilders;

use TreeSoft\Specifications\Generators\TypeExtractor as ParentTypeExtractor;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class TypeExtractor extends ParentTypeExtractor
{
    /**
     * @param object $schema
     *
     * @return null|string
     */
    public function extractClass($schema)
    {
        if (isset($schema->classGenerator->class)) {
            $class = $this->extendNamespace($schema->classGenerator->class);
        }

        return $class ?? parent::extractClass($schema);
    }
}
