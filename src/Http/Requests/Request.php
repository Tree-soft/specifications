<?php

namespace Mildberry\Specifications\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Mildberry\Specifications\Objects\RequestInterface;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class Request extends FormRequest implements ContainerAwareInterface, RequestInterface
{
    use ContainerAwareTrait;

    public function validate()
    {
    }

    public function getHeaders(): array
    {
        return $this->headers->all();
    }

    public function getQuery(): array
    {
        return $this->query->all();
    }

    public function getData(): array
    {
        return $this->all();
    }

    public function getRoute(): array
    {
        return $this->route()->parameters();
    }
}
