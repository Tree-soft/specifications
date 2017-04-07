<?php

use TreeSoft\Specifications\DAL\Factories\DefaultFactory;
use TreeSoft\Specifications\DAL\Eloquent\TransactionManager;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
return [
    'factory' => DefaultFactory::class,
    'transaction' => TransactionManager::class,
    'repositories' => [],
];
