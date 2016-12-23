<?php

namespace Mildberry\Specifications\Generators;

use League\JsonGuard\Reference;
use Mildberry\Specifications\Exceptions\UndefinedSchemaIdException;
use Mildberry\Specifications\Generators\ClassBuilders\Factory;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ClassGenerator extends AbstractGenerator
{
    /**
     * @var string
     */
    private $namespace = '\\';

    /**
     * @param object $schema
     *
     * @throws UndefinedSchemaIdException
     */
    public function generate($schema)
    {
        while ($schema instanceof Reference) {
            $schema = $schema->resolve();
        }

        $this->output->write(
            $this->getFilename($schema),
            $this->getContent($schema)
        );
    }

    /**
     * @param object $schema
     *
     * @return string
     */
    public function getFilename($schema): string
    {
        return
            $schema->classGenerator->filename ??
            $this->getFileNameByClass($schema) ??
            $this->getFilenameById($schema);
    }

    /**
     * @param object $schema
     *
     * @throws UndefinedSchemaIdException
     *
     * @return string
     */
    protected function getFilenameById($schema): string
    {
        $parts = $this->getSchemaIdParts($schema);

        return implode(DIRECTORY_SEPARATOR, $parts) . '.php';
    }

    /**
     * @param object $schema
     *
     * @return string
     */
    public function getClassName($schema): string
    {
        $namespace = $schema->classGenerator->class ??
            $this->getClassNameById($schema);

        $parts = array_filter(
            array_map(function ($namespace) {
                return trim($namespace, '\\');
            }, [$this->namespace, $namespace])
        );

        return '\\' . implode('\\', $parts);
    }

    /**
     * @param object $schema
     *
     * @return string
     */
    protected function getClassNameById($schema): string
    {
        $parts = $this->getSchemaIdParts($schema);

        return implode('\\', $parts);
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
     * @param object $schema
     *
     * @return string|null
     */
    protected function getFileNameByClass($schema)
    {
        if (empty($schema->classGenerator->class)) {
            return null;
        }

        return str_replace('\\', DIRECTORY_SEPARATOR, $schema->classGenerator->class) . '.php';
    }

    /**
     * @param object $schema
     *
     * @return string
     */
    protected function getContent($schema): string
    {
        $factory = $this->createFactory();
        $builder = $factory->create($schema);

        $builder
            ->setGenerator($this);

        return $builder->build();
    }

    /**
     * @return BuilderFactory
     */
    public function createFactory(): BuilderFactory
    {
        return $this->container->make(Factory::class);
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
}
