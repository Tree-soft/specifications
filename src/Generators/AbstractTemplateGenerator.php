<?php

namespace Mildberry\Specifications\Generators;

use Illuminate\View\Engines\CompilerEngine;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;
use Illuminate\View\Factory as ViewFactory;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class AbstractTemplateGenerator implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $ext = 'blade.php';

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

        return $engine->get("{$this->path}/{$file}.{$this->ext}", array_merge($shared, $data));
    }
}
