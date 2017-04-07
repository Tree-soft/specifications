<?php

namespace TreeSoft\Specifications\Generators;

use TreeSoft\Specifications\Exceptions\UndefinedSchemaIdException;
use TreeSoft\Specifications\Generators\ClassBuilders\Factory;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ClassGenerator extends AbstractGenerator
{
    /**
     * @param object $schema
     *
     * @throws UndefinedSchemaIdException
     */
    public function generate($schema)
    {
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
    protected function getContent($schema): string
    {
        $factory = $this->createFactory();
        $factory
            ->setExtractor($this->extractor);

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
     * @param object $schema
     *
     * @return string
     */
    public function getFilename($schema): string
    {
        return
            $schema->classGenerator->filename ??
            parent::getFilename($schema);
    }
}
