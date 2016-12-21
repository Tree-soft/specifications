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
    protected $headersSchema = 'schema://common/empty';

    /**
     * @var string
     */
    protected $querySchema = 'schema://common/empty';

    /**
     * @var string
     */
    protected $dataSchema = 'schema://common/empty';

    /**
     * @param RequestInterface $request
     */
    public function check($request)
    {
        $this
            ->checkBlock(HeaderSpecification::class, $this->headersSchema, $request->getHeaders())
            ->checkBlock(QuerySpecification::class, $this->querySchema, $request->getQuery())
            ->checkBlock(DataSpecification::class, $this->dataSchema, $request->getData());
    }

    /**
     * @param string $class
     * @param string|object $schema
     * @param mixed $data
     *
     * @return $this
     */
    protected function checkBlock(string $class, $schema, $data)
    {
        $blockSpecification = $this->container->make($class);
        $blockSpecification->setSchema($schema);
        $blockSpecification->check($data);

        return $this;
    }
}
