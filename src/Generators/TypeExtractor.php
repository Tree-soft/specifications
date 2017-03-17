<?php

namespace Mildberry\Specifications\Generators;

use Mildberry\Specifications\Exceptions\UndefinedSchemaIdException;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class TypeExtractor
{
    const BOOL = 'bool';
    const INT = 'int';
    const OBJECT = 'object';
    const ARRAY = 'array';

    /**
     * @var string
     */
    private $namespace = '\\';

    /**
     * @var array
     */
    private $bindings = [];

    /**
     * @param object $schema
     *
     * @return string|string[]
     */
    public function extract($schema)
    {
        $schema = $this->extendSchema($schema);

        if (isset($schema->oneOf)) {
            return array_map(function ($schema) {
                return $this->extract($schema);
            }, $schema->oneOf);
        }

        return
            $this->extractClass($schema) ??
            $this->extractSimple($schema);
    }

    /**
     * @param object $schema
     *
     * @return object
     */
    public function extendSchema($schema)
    {
        if (empty($schema->allOf)) {
            return $schema;
        }

        $schema = array_reduce($schema->allOf, function ($carry, $schema) {
            return $this->mergeSchema($carry, $schema);
        }, $schema);

        return $schema;
    }

    /**
     * @param object $schema
     *
     * @return string|null
     */
    protected function extractSimple($schema)
    {
        $type = $schema->type;

        return [
            'integer' => self::INT,
            'boolean' => self::BOOL,
        ][$type] ?? $type;
    }

    /**
     * @param object $schema
     *
     * @return string|null
     */
    protected function extractClass($schema)
    {
        if (
            ($this->extractSimple($schema) != self::OBJECT) ||
            empty($schema->id)
        ) {
            return null;
        }

        return $this->bindings[$schema->id] ??
            $this->getSchemaByParts($schema);
    }

    /**
     * @param mixed $schema
     *
     * @return string
     */
    protected function getSchemaByParts($schema): string
    {
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
        $namespace = rtrim($this->namespace, '\\');
        $regexp = preg_quote("{$namespace}\\");

        return preg_replace("#^{$regexp}#", '', $class);
    }

    /**
     * @param object $source
     * @param object $schema
     *
     * @return object
     */
    protected function mergeSchema($source, $schema)
    {
        $result = (array) $source;

        $result = array_merge((array) $schema, $result);

        foreach ($result as $key => &$value) {
            if (is_object($value)) {
                $value = $this->mergeSchema($value, ($schema->{$key} ?? (object) []));
            }
        }

        return (object) $result;
    }

    /**
     * @return array
     */
    public function getBindings(): array
    {
        return $this->bindings;
    }

    /**
     * @param array $bindings
     *
     * @return $this
     */
    public function setBindings(array $bindings)
    {
        $this->bindings = $bindings;

        return $this;
    }
}
