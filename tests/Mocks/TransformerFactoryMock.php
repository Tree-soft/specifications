<?php

namespace TreeSoft\Tests\Specifications\Mocks;

use TreeSoft\Specifications\Transforming\TransformerFactory;
use TreeSoft\Specifications\Transforming\Transformers\AbstractTransformer;
use PHPUnit\Framework\TestCase;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class TransformerFactoryMock extends TransformerFactory
{
    /**
     * @var AbstractTransformer
     */
    private $transformer;

    /**
     * @var string
     */
    private $from;

    /**
     * @var string
     */
    private $to;

    /**
     * @param string $from
     * @param string $to
     *
     * @return AbstractTransformer
     */
    public function create($from, $to): AbstractTransformer
    {
        if (isset($this->from)) {
            TestCase::assertEquals($this->from, $from);
        }

        if (isset($this->to)) {
            TestCase::assertEquals($this->to, $to);
        }

        return $this->transformer;
    }

    /**
     * @return AbstractTransformer
     */
    public function getTransformer(): AbstractTransformer
    {
        return $this->transformer;
    }

    /**
     * @param AbstractTransformer $transformer
     *
     * @return $this
     */
    public function setTransformer(AbstractTransformer $transformer)
    {
        $this->transformer = $transformer;

        return $this;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @param string $from
     *
     * @return $this
     */
    public function setFrom(string $from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @param string $to
     *
     * @return $this
     */
    public function setTo(string $to)
    {
        $this->to = $to;

        return $this;
    }
}
