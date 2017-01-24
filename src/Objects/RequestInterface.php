<?php

namespace Mildberry\Specifications\Objects;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
interface RequestInterface
{
    /**
     * @return object
     */
    public function getHeaders();

    /**
     * @return object
     */
    public function getQuery();

    /**
     * @return object
     */
    public function getData();

    /**
     * @return object
     */
    public function getRoute();
}
