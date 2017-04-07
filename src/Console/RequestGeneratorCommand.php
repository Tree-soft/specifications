<?php

namespace TreeSoft\Specifications\Console;

use Illuminate\Console\Command;
use TreeSoft\Specifications\Generators\Request\RequestGenerator;
use TreeSoft\Specifications\Support\FileWriter;
use Rnr\Resolvers\Interfaces\ConfigAwareInterface;
use Rnr\Resolvers\Traits\ConfigAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class RequestGeneratorCommand extends Command implements ConfigAwareInterface
{
    use ConfigAwareTrait;

    /**
     * @var string
     */
    protected $signature =
        'specification:request-generate
        {--output=. : Directory for saving class of new request}
        {--header= : Header schema}
        {--query= : Query schema}
        {--data= : Data schema}
        {--route= : Route schema}
        {--base= : Base class of request}
        {class : Class name of request} ';

    /**
     * @var string
     */
    protected $description = 'Generate request.';

    /**
     * @param RequestGenerator $generator
     * @param FileWriter $output
     */
    public function handle(RequestGenerator $generator, FileWriter $output)
    {
        $class = $this->argument('class');

        $generator
            ->setHeaderSchema($this->option('header'))
            ->setQuerySchema($this->option('query'))
            ->setDataSchema($this->option('data'))
            ->setRouteSchema($this->option('route'))
            ->setClass($class);

        $namespace = $this->option('namespace');

        if (isset($namespace)) {
            $generator->setNamespace($namespace);
        }

        $baseClass = $this->option('base');

        if (isset($baseClass)) {
            $generator
                ->setBaseClass($baseClass);
        }

        $output->setPath($this->option('output'));

        $path = str_replace(DIRECTORY_SEPARATOR, '/', $class);

        $output->write("{$path}.php", $generator->generate());
    }
}
