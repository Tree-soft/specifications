<?php

namespace Mildberry\Specifications\Generators;

use League\JsonGuard\Reference;
use Mildberry\Specifications\Exceptions\UndefinedSchemaIdException;
use Mildberry\Specifications\Generators\ClassBuilders\Factory;
use Mildberry\Specifications\Generators\ClassBuilders\TypeExtractor;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ClassGenerator extends AbstractGenerator
{
    /**
     * @var TypeExtractor
     */
    private $extractor;

    /**
     * @param object $schema
     *
     * @throws UndefinedSchemaIdException
     */
    public function generate($schema)
    {
        while ($schema instanceof Reference) {
            $schema = $schema->resolve();
        }

        $this->output->write(
            $this->getFilename($schema),
            $this->getContent($schema)
        );
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
            $this->extractor->extractClass($schema)
        );

        return str_replace('\\', DIRECTORY_SEPARATOR, ltrim($class, '\\')) . '.php';
    }

    /**
     * @param object $schema
     *
     * @return string
     */
    protected function getContent($schema): string
    {
        $factory = $this->createFactory();
        $builder = $factory->create($schema);

        $builder
            ->setGenerator($this);

        return $builder->build();
    }

    /**
     * @return BuilderFactory
     */
    public function createFactory(): BuilderFactory
    {
        return $this->container->make(Factory::class);
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
