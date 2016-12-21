<?php

namespace Mildberry\Specifications\Specifications\Request;

use Mildberry\Specifications\Specifications\AbstractSpecification;
use Mildberry\Specifications\Support\DynamicSchemaInjectorTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class HeaderSpecification extends AbstractSpecification
{
    use DynamicSchemaInjectorTrait;

    public function check($data)
    {
        // TODO: Implement check() method.
    }
}
