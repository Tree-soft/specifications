<?php

namespace Mildberry\Specifications\Generators;

use Mildberry\Specifications\Exceptions\UndefinedSchemaIdException;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class TypeExtractor
{
    /**
     * @var string
     */
    private $namespace = '\\';

    /**
     * @param object $schema
     *
     * @return string|string[]
     */
    public function extract($schema)
    {
        return
            $this->extractMultiple($schema) ??
            $this->extractClass($schema) ??
            $this->extractSimple($schema);
    }

    /**
     * @param object $schema
     *
     * @return array|null
     */
    public function extractMultiple($schema)
    {
        $type = $schema->type;

        if (!is_object($type)) {
            return null;
        }

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

    /**
     * @param object $schema
     *
     * @return string|null
     */
    public function extractSimple($schema)
    {
        $type = $schema->type;

        return [
            'integer' => 'int',
            'boolean' => 'bool',
        ][$type] ?? $type;
    }

    /**
     * @param object $schema
     *
     * @return string|null
     */
    public function extractClass($schema)
    {
        if (
            ($this->extractSimple($schema) != 'object') ||
            empty($schema->id)
        ) {
            return null;
        }

        $parts = $this->getSchemaIdParts($schema);

        $namespace = implode('\\', $parts);

        return $this->extendNamespace($namespace);
    }

    /**
     * @param string $class
     *
     * @return string
     */
    protected function extendNamespace($class)
    {
        $parts = array_filter(
            array_map(function ($namespace) {
                return trim($namespace, '\\');
            }, [$this->namespace, $class])
        );

        return '\\' . implode('\\', $parts);
    }

    /**
     * @param object $schema
     *
     * @throws UndefinedSchemaIdException
     *
     * @return array
     */
    protected function getSchemaIdParts($schema): array
    {
        if (empty($schema->id)) {
            $exception = new UndefinedSchemaIdException('Undefined id of schema');

            $exception
                ->setSchema($schema);

            throw $exception;
        }

        $url = parse_url($schema->id);

        $parts = explode('/', $url['path'] ?? '');

        if ($url['scheme'] == 'schema') {
            array_unshift($parts, $url['host']);
        }

        return array_map(
            function ($part) {
                return studly_case(str_replace('-', '_', $part));
            }, array_filter(
                array_map(function ($part) {
                    return trim($part, '/');
                }, $parts),
                function ($part) {
                    return !empty(trim($part, '/'));
                }
            )
        );
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     *
     * @return $this
     */
    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * @param string $class
     *
     * @return string
     */
    public function getShortName(string $class): string
    {
        return str_replace("{$this->namespace}\\", '', $class);
    }
}
