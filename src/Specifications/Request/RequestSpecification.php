<?php

namespace Mildberry\Specifications\Specifications\Request;

use Mildberry\Specifications\Objects\RequestInterface;
use Mildberry\Specifications\Specifications\AbstractSpecification;

/**
 * @author Sergei Melnikov <me@rnr.name>
 *
 * @method
 */
class RequestSpecification extends AbstractSpecification
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
     * @var HeaderSpecification
     */
    protected $headerSpecification;

    /**
     * @var QuerySpecification
     */
    protected $querySpecification;

    /**
     * @var DataSpecification
     */
    protected $dataSpecification;

    /**
     * @param RequestInterface $request
     */
    public function check($request)
    {
        $this->getHeaderSpecification()->check($request->getHeaders());
        $this->getQuerySpecification()->check($request->getQuery());
        $this->getDataSpecification()->check($request->getData());
    }

    /**
     * @return HeaderSpecification
     */
    public function getHeaderSpecification(): HeaderSpecification
    {
        if (empty($this->headerSpecification)) {
            $this->headerSpecification = $this->createBlock(HeaderSpecification::class, $this->headerSchema);
        }

        return $this->headerSpecification;
    }

    /**
     * @return QuerySpecification
     */
    public function getQuerySpecification(): QuerySpecification
    {
        if (empty($this->querySpecification)) {
            $this->querySpecification = $this->createBlock(QuerySpecification::class, $this->querySchema);
        }

        return $this->querySpecification;
    }

    /**
     * @return DataSpecification
     */
    public function getDataSpecification(): DataSpecification
    {
        if (empty($this->dataSpecification)) {
            $this->dataSpecification = $this->createBlock(DataSpecification::class, $this->dataSchema);
        }

        return $this->dataSpecification;
    }

    /**
     * @param string $class
     * @param string|object $schema
     *
     * @return AbstractSpecification
     */
    protected function createBlock(string $class, $schema)
    {
        $blockSpecification = $this->container->make($class);
        $blockSpecification->setSchema($schema);

        return $blockSpecification;
    }
}
