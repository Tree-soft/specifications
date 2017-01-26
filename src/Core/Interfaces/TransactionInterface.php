<?php

namespace Mildberry\Specifications\Core\Interfaces;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
interface TransactionInterface
{
    public function start();

    public function commit();

    public function rollback();
}
