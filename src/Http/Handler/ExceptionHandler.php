<?php

namespace TreeSoft\Specifications\Http\Handler;

use Illuminate\Foundation\Exceptions\Handler;
use Exception;
use TreeSoft\Specifications\Exceptions\EntityValidationException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ExceptionHandler extends Handler
{
    /**
     * @param Exception $e
     *
     * @return Exception
     */
    protected function prepareException(Exception $e)
    {
        $e = parent::prepareException($e);

        if ($e instanceof EntityValidationException) {
            $e = new UnprocessableEntityHttpException($e->getMessage(), $e, $e->getCode());
        }

        return $e;
    }
}
