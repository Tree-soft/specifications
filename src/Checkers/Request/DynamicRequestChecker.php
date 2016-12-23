<?php

namespace Mildberry\Specifications\Checkers\Request;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class DynamicRequestChecker extends RequestChecker
{
    /**
     * @return string
     */
    public function getHeaderSchema(): string
    {
        return $this->headerSchema;
    }

    /**
     * @param string $headerSchema
     *
     * @return $this
     */
    public function setHeaderSchema(string $headerSchema)
    {
        $this->headerSchema = $headerSchema;

        return $this;
    }

    /**
     * @return string
     */
    public function getQuerySchema(): string
    {
        return $this->querySchema;
    }

    /**
     * @param string $querySchema
     *
     * @return $this
     */
    public function setQuerySchema(string $querySchema)
    {
        $this->querySchema = $querySchema;

        return $this;
    }

    /**
     * @return string
     */
    public function getDataSchema(): string
    {
        return $this->dataSchema;
    }

    /**
     * @param string $dataSchema
     *
     * @return $this
     */
    public function setDataSchema(string $dataSchema)
    {
        $this->dataSchema = $dataSchema;

        return $this;
    }

    /**
     * @return string
     */
    public function getRouteSchema(): string
    {
        return $this->routeSchema;
    }

    /**
     * @param string $routeSchema
     *
     * @return $this
     */
    public function setRouteSchema(string $routeSchema)
    {
        $this->routeSchema = $routeSchema;

        return $this;
    }
}
