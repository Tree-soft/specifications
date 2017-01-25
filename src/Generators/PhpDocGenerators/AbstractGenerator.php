<?php

namespace Mildberry\Specifications\Generators\PhpDocGenerators;

use Mildberry\Specifications\Generators\AbstractTemplateGenerator;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class AbstractGenerator extends AbstractTemplateGenerator
{
    /**
     * AbstractGenerator constructor.
     */
    public function __construct()
    {
        $this->path = dirname(dirname(dirname(__DIR__))) . '/resources/templates/phpdoc';
        $this->ext = 'phpdoc';
    }
}
