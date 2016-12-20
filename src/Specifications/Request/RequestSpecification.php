<?php

namespace Mildberry\Specifications\Specifications\Request;

use Mildberry\Specifications\Objects\RequestInterface;
use Mildberry\Specifications\Specifications\SpecificationInterface;

/**
 * @author Sergei Melnikov <me@rnr.name>
 * @met
 */
class RequestSpecification implements SpecificationInterface
{
    /**
     * @var string
     */
    protected $headersSchema = 'schema:common/empty';

    /**
     * @var string
     */
    protected $querySchema = 'schema:common/empty';

    /**
     * @var string
     */
    protected $dataSchema = 'schema:common/empty';

    /**
     * @var HeaderSpecification
     */
    private $headersSpecification;

    /**
     * @var QuerySpecification
     */
    private $querySpecification;

    /**
     * @var DataSpecification
     */
    private $dataSpecification;

    public function __construct()
    {
        $this->headersSpecification = new HeaderSpecification($this->headersSchema);
        $this->querySpecification = new QuerySpecification($this->querySchema);
        $this->dataSpecification = new DataSpecification($this->dataSpecification);
    }

    /**
     * @param RequestInterface $request
     */
    public function check($request)
    {
        $this->headersSpecification->check($request->getHeaders());
        $this->querySpecification->check($request->getQuery());
        $this->dataSpecification->check($request->getData());
    }
}
