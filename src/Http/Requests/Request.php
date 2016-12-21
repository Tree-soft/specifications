<?php

namespace Mildberry\Specifications\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Mildberry\Specifications\Exceptions\EntityValidationException;
use Mildberry\Specifications\Objects\RequestInterface;
use Mildberry\Specifications\Specifications\Request\DynamicRequestSpecification;
use Mildberry\Specifications\Specifications\Request\RequestSpecification;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class Request extends FormRequest implements ContainerAwareInterface, RequestInterface
{
    use ContainerAwareTrait;

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
     * @throws EntityValidationException
     */
    public function validate()
    {
        $specification = $this->createSpecification();
        $specification->check($this);
    }

    protected function createSpecification(): RequestSpecification
    {
        /**
         * @var DynamicRequestSpecification $specification
         */
        $specification = $this->container->make(DynamicRequestSpecification::class);

        $specification
            ->setHeaderSchema($this->headerSchema)
            ->setQuerySchema($this->querySchema)
            ->setDataSchema($this->dataSchema)
            ->setRouteSchema($this->routeSchema);

        return $specification;
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
