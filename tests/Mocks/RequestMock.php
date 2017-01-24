<?php

namespace Mildberry\Tests\Specifications\Mocks;

use Mildberry\Specifications\Objects\RequestInterface;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class RequestMock implements RequestInterface
{
    /**
     * @var object
     */
    private $headers = [];

    /**
     * @var object
     */
    private $query = [];

    /**
     * @var object
     */
    private $data = [];

    /**
     * @var object
     */
    private $route = [];

    /**
     * @return object
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param object $headers
     *
     * @return $this
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @return object
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param object $query
     *
     * @return $this
     */
    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * @return object
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param object $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return object
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param object $route
     *
     * @return $this
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }
}
