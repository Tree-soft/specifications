<?php

namespace Mildberry\Specifications\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Mildberry\Specifications\Exceptions\EntityValidationException;
use Mildberry\Specifications\Objects\RequestInterface;
use Mildberry\Specifications\Checkers\Request\DynamicRequestChecker;
use Mildberry\Specifications\Checkers\Request\RequestChecker;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class Request extends FormRequest implements RequestInterface
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
     * @throws EntityValidationException
     */
    public function validate()
    {
        $specification = $this->createSpecification();
        $specification->check($this);
    }

    protected function createSpecification(): RequestChecker
    {
        /**
         * @var DynamicRequestChecker $specification
         */
        $specification = $this->container->make(DynamicRequestChecker::class);

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
