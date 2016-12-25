<?php

namespace Mildberry\Specifications\Generators\PhpDocGenerators;

use Illuminate\View\Engines\CompilerEngine;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;
use Illuminate\View\Factory as ViewFactory;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class AbstractGenerator implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var string
     */
    protected $path;

    /**
     * AbstractGenerator constructor.
     */
    public function __construct()
    {
        $this->path = dirname(dirname(dirname(__DIR__))) . '/resources/phpdoc-templates';
    }

    /**
     * @param string $file
     * @param array $data
     *
     * @return string
     */
    public function loadTemplate(string $file, array $data = []): string
    {
        /**
         * @var ViewFactory $viewFactory
         */
        $viewFactory = $this->container->make('view');

        /**
         * @var CompilerEngine $engine
         */
        $engine = $viewFactory->getEngineResolver()->resolve('blade');

        $shared = [
            '__env' => $viewFactory,
        ];

        return $engine->get("{$this->path}/{$file}.phpdoc", array_merge($shared, $data));
    }
}
