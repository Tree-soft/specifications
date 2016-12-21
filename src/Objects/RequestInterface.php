<?php

namespace Mildberry\Specifications\Objects;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
interface RequestInterface
{
    /**
     * @return array
     */
    public function getHeaders(): array;

    /**
     * @return array
     */
    public function getQuery(): array;

    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @return array
     */
    public function getRoute(): array;
}
