<?php

namespace Mildberry\Specifications\Generators\Request;

use Mildberry\Specifications\Generators\AbstractTemplateGenerator;
use Mildberry\Specifications\Http\Requests\Request;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class RequestGenerator extends AbstractTemplateGenerator
{
    /**
     * @var string|null
     */
    private $headerSchema;

    /**
     * @var string|null
     */
    private $querySchema;

    /**
     * @var string|null
     */
    private $dataSchema;

    /**
     * @var string|null
     */
    private $routeSchema;

    /**
     * @var string
     */
    private $baseClass = Request::class;

    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $namespace;

    /**
     * RequestGenerator constructor.
     */
    public function __construct()
    {
        $this->path = dirname(dirname(dirname(__DIR__))) . '/resources/templates';
    }

    /**
     * @return string
     */
    public function generate(): string
    {
        $class = $this->divideClass($this->joinClassName($this->namespace, $this->class));
        $base = $this->divideClass($this->baseClass);

        $alias = ($class['class'] == $base['class']) ? ("Parent{$base['class']}") : (null);

        return $this->loadTemplate('request', [
            'requestNamespace' => $class['namespace'],
            'requestClass' => $class['class'],
            'baseNamespace' => $this->baseClass,
            'baseClass' => $base['class'],
            'alias' => $alias,
            'headerSchema' => $this->headerSchema,
            'querySchema' => $this->querySchema,
            'dataSchema' => $this->dataSchema,
            'routeSchema' => $this->routeSchema,
        ]);
    }

    /**
     * @param string $class
     *
     * @return array
     */
    public function divideClass(string $class): array
    {
        $parts = explode('\\', $class);

        $partClass = array_pop($parts);

        return [
            'namespace' => implode('\\', $parts),
            'class' => $partClass,
        ];
    }

    /**
     * @param string $namespace
     * @param string $class
     *
     * @return string
     */
    public function joinClassName(string $namespace, string $class): string
    {
        return implode('\\', [rtrim($namespace, '\\'), ltrim($class, '\\')]);
    }

    /**
     * @return string|null
     */
    public function getHeaderSchema()
    {
        return $this->headerSchema;
    }

    /**
     * @param string|null $headerSchema
     *
     * @return $this
     */
    public function setHeaderSchema($headerSchema)
    {
        $this->headerSchema = $headerSchema;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getQuerySchema()
    {
        return $this->querySchema;
    }

    /**
     * @param string|null $querySchema
     *
     * @return $this
     */
    public function setQuerySchema($querySchema)
    {
        $this->querySchema = $querySchema;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDataSchema()
    {
        return $this->dataSchema;
    }

    /**
     * @param string|null $dataSchema
     *
     * @return $this
     */
    public function setDataSchema($dataSchema)
    {
        $this->dataSchema = $dataSchema;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRouteSchema()
    {
        return $this->routeSchema;
    }

    /**
     * @param string|null $routeSchema
     *
     * @return $this
     */
    public function setRouteSchema($routeSchema)
    {
        $this->routeSchema = $routeSchema;

        return $this;
    }

    /**
     * @return string
     */
    public function getBaseClass(): string
    {
        return $this->baseClass;
    }

    /**
     * @param string $baseClass
     *
     * @return $this
     */
    public function setBaseClass(string $baseClass)
    {
        $this->baseClass = $baseClass;

        return $this;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param string $class
     *
     * @return $this
     */
    public function setClass(string $class)
    {
        $this->class = $class;

        return $this;
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
