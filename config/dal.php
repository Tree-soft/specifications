<?php

use Mildberry\Specifications\DAL\Factories\DefaultFactory;
use Mildberry\Specifications\DAL\Eloquent\TransactionManager;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
return [
    'factory' => DefaultFactory::class,
    'transaction' => TransactionManager::class,
    'repositories' => [],
];
