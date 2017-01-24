<?php

namespace Mildberry\Specifications\Console;

use Illuminate\Console\Command;
use Mildberry\Specifications\Generators\ClassBuilders\TypeExtractor;
use Mildberry\Specifications\Generators\ClassGenerator;
use Mildberry\Specifications\Schema\LaravelFactory;
use Mildberry\Specifications\Support\FileWriter;
use Rnr\Resolvers\Interfaces\ConfigAwareInterface;
use Rnr\Resolvers\Traits\ConfigAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ClassGeneratorCommand extends Command implements ConfigAwareInterface
{
    use ConfigAwareTrait;

    /**
     * @var string
     */
    protected $signature =
        'specification:class-generate
        {--output=. : Directory to write new classes}
        {--namespace=\ : Root namespace for classes}
        {schema* : Uri of schema files of entities that should be generated.}';

    /**
     * @var string
     */
    protected $description = 'Generate class from specification.';

    /**
     * @param ClassGenerator $generator
     * @param FileWriter $output
     * @param LaravelFactory $factory
     */
    public function handle(ClassGenerator $generator, FileWriter $output, LaravelFactory $factory)
    {
        $extractor = new TypeExtractor();

        $extractor->setNamespace(
            $this->option('namespace') ??
            $this->config->get('specifications.namespace')
        );

        $output->setPath($this->option('output'));

        $generator
            ->setExtractor($extractor)
            ->setOutput($output);

        foreach ($this->argument('schema') as $value) {
            $schema = $factory->schema($value);
            $generator->generate($factory->schema($schema));
            $fileName = $output->getPath() . DIRECTORY_SEPARATOR . $generator->getFilename($schema);
            $this->info("{$value} was saved to {$fileName}");
        }
    }
}
