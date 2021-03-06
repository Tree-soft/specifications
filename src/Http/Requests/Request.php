<?php

namespace TreeSoft\Specifications\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use TreeSoft\Specifications\Exceptions\EntityValidationException;
use TreeSoft\Specifications\Objects\RequestInterface;
use TreeSoft\Specifications\Checkers\Request\DynamicRequestChecker;
use TreeSoft\Specifications\Checkers\Request\RequestChecker;
use TreeSoft\Specifications\Support\DataPreparator;

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

    /**
     * @return RequestChecker
     */
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

    /**
     * @return object
     */
    public function getHeaders()
    {
        return $this->prepareData($this->headers->all());
    }

    /**
     * @return object
     */
    public function getQuery()
    {
        return $this->prepareData($this->query->all());
    }

    /**
     * @return object
     */
    public function getData()
    {
        return $this->prepareData($this->all());
    }

    /**
     * @return object
     */
    public function getRoute()
    {
        return $this->prepareData($this->route()->parameters());
    }

    /**
     * @return string
     */
    public function getHeaderSchema(): string
    {
        return $this->headerSchema;
    }

    /**
     * @return string
     */
    public function getDataSchema(): string
    {
        return $this->dataSchema;
    }

    /**
     * @return string
     */
    public function getQuerySchema(): string
    {
        return $this->querySchema;
    }

    /**
     * @return string
     */
    public function getRouteSchema(): string
    {
        return $this->routeSchema;
    }

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    public function prepareData($data)
    {
        $preparator = new DataPreparator();

        return $preparator->prepare($data);
    }
}
