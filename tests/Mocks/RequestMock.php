<?php

namespace Mildberry\Tests\Specifications\Mocks;

use Mildberry\Specifications\Objects\RequestInterface;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class RequestMock implements RequestInterface
{
    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var array
     */
    private $query = [];

    /**
     * @var array
     */
    private $data = [];

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     *
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @return array
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    /**
     * @param array $query
     *
     * @return $this
     */
    public function setQuery(array $query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }
}
