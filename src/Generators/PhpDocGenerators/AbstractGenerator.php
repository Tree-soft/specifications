<?php

namespace TreeSoft\Specifications\Generators\PhpDocGenerators;

use TreeSoft\Specifications\Generators\AbstractTemplateGenerator;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class AbstractGenerator extends AbstractTemplateGenerator
{
    /**
     * @return string
     */
    abstract public function compile(): string;

    /**
     * AbstractGenerator constructor.
     */
    public function __construct()
    {
        $this->path = dirname(dirname(dirname(__DIR__))) . '/resources/templates/phpdoc';
        $this->ext = 'phpdoc';
    }
}
