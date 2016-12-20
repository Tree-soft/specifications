<?php

namespace Mildberry\Specifications\Specifications;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
interface SpecificationInterface
{
    public function check($data);
}
