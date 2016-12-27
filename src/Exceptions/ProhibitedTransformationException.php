<?php

namespace Mildberry\Specifications\Exceptions;

use Exception;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ProhibitedTransformationException extends Exception
{
    /**
     * @var mixed
     */
    private $from;

    /**
     * @var mixed
     */
    private $to;

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param mixed $from
     *
     * @return $this
     */
    public function setFrom($from)
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param mixed $to
     *
     * @return $this
     */
    public function setTo($to)
    {
        $this->to = $to;

        return $this;
    }
}
