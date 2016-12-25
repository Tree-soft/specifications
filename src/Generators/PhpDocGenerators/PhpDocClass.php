<?php

namespace Mildberry\Specifications\Generators\PhpDocGenerators;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class PhpDocClass extends AbstractGenerator
{
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->loadTemplate('class');
    }
}
