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
    public function compile(): string
    {
        return $this->loadTemplate('class');
    }
}
