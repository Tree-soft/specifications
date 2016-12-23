<?php

namespace Mildberry\Specifications\Generators;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class TypeExtractor
{
    /**
     * @param object $schema
     *
     * @return string|string[]
     */
    public function extract($schema)
    {
        $type = $schema->type;

        if (is_object($type)) {
            return $this->extractMultiple($type);
        }

        $map = [
            'integer' => 'int',
            'boolean' => 'bool',
        ];

        return $map[$type] ?? $type;
    }

    protected function extractMultiple($type): array
    {
        $rawTypes = array_map(function ($schema) {
            return $this->extract($schema);
        }, $type->allOf ?? $type->anyOf ?? $type->oneOf);

        $types = [];

        foreach ($rawTypes as $type) {
            if (is_array($type)) {
                $types = array_merge($types, $type);
            } else {
                $types[] = $type;
            }
        }

        return array_values(array_unique($types));
    }
}
