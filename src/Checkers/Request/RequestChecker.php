<?php

namespace TreeSoft\Specifications\Checkers\Request;

use TreeSoft\Specifications\Exceptions\EntityValidationException;
use TreeSoft\Specifications\Objects\RequestInterface;
use TreeSoft\Specifications\Checkers\AbstractChecker;

/**
 * @author Sergei Melnikov <me@rnr.name>
 *
 */
class RequestChecker extends AbstractChecker
{
    /**
     * @var string
     */
    protected $headerSchema = 'schema://common/empty';

    /**
     * @var string
     */
    protected $querySchema = 'schema://common/empty';

    /**
     * @var string
     */
    protected $dataSchema = 'schema://common/empty';

    /**
     * @var string
     */
    protected $routeSchema = 'schema://common/empty';

    /**
     * @var HeaderChecker
     */
    protected $headerSpecification;

    /**
     * @var QueryChecker
     */
    protected $querySpecification;

    /**
     * @var DataChecker
     */
    protected $dataSpecification;

    /**
     * @var RouteChecker
     */
    protected $routeSpecification;

    /**
     * @param RequestInterface $request
     *
     * @throws EntityValidationException
     */
    public function check($request)
    {
        $this->getRouteSpecification()->check($request->getRoute());
        $this->getHeaderSpecification()->check($request->getHeaders());
        $this->getQuerySpecification()->check($request->getQuery());
        $this->getDataSpecification()->check($request->getData());
    }

    /**
     * @return HeaderChecker
     */
    public function getHeaderSpecification(): HeaderChecker
    {
        if (empty($this->headerSpecification)) {
            $this->headerSpecification = $this->createBlock(HeaderChecker::class, $this->headerSchema);
        }

        return $this->headerSpecification;
    }

    /**
     * @return QueryChecker
     */
    public function getQuerySpecification(): QueryChecker
    {
        if (empty($this->querySpecification)) {
            $this->querySpecification = $this->createBlock(QueryChecker::class, $this->querySchema);
        }

        return $this->querySpecification;
    }

    /**
     * @return DataChecker
     */
    public function getDataSpecification(): DataChecker
    {
        if (empty($this->dataSpecification)) {
            $this->dataSpecification = $this->createBlock(DataChecker::class, $this->dataSchema);
        }

        return $this->dataSpecification;
    }

    public function getRouteSpecification(): RouteChecker
    {
        if (empty($this->routeSpecification)) {
            $this->routeSpecification = $this->createBlock(RouteChecker::class, $this->routeSchema);
        }

        return $this->routeSpecification;
    }

    /**
     * @param string $class
     * @param string|object $schema
     *
     * @return AbstractChecker
     */
    protected function createBlock(string $class, $schema)
    {
        $blockSpecification = $this->container->make($class);
        $blockSpecification->setSchema($schema);

        return $blockSpecification;
    }
}
