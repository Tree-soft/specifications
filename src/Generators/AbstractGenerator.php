<?php

namespace Mildberry\Specifications\Generators;

use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class AbstractGenerator implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var TypeExtractor
     */
    protected $extractor;

    /**
     * @param object $schema
     */
    abstract public function generate($schema);

    /**
     * @return OutputInterface
     */
    public function getOutput(): OutputInterface
    {
        return $this->output;
    }

    /**
     * @param OutputInterface $output
     *
     * @return $this
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * @param object $schema
     *
     * @return string
     */
    public function getFilename($schema): string
    {
        return
            $schema->classGenerator->filename ??
            $this->getFileNameByClass($schema);
    }

    /**
     * @param object $schema
     *
     * @return string|null
     */
    protected function getFileNameByClass($schema)
    {
        $class = $this->extractor->getShortName(
            $this->extractor->extract($schema)
        );

        return str_replace('\\', DIRECTORY_SEPARATOR, ltrim($class, '\\')) . '.php';
    }

    /**
     * @return TypeExtractor
     */
    public function getExtractor(): TypeExtractor
    {
        return $this->extractor;
    }

    /**
     * @param TypeExtractor $extractor
     *
     * @return $this
     */
    public function setExtractor(TypeExtractor $extractor)
    {
        $this->extractor = $extractor;

        return $this;
    }
}
