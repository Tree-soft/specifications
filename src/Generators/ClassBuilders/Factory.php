<?php

namespace Mildberry\Specifications\Generators\ClassBuilders;

use Mildberry\Specifications\Exceptions\UnknownTypeException;
use Mildberry\Specifications\Generators\AbstractBuilder;
use Mildberry\Specifications\Generators\BuilderFactory;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class Factory extends BuilderFactory implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var array|AbstractBuilder[]
     */
    protected $types = [
        'object' => ObjectBuilder::class,
    ];

    /**
     * @param object $schema
     *
     * @throws UnknownTypeException
     *
     * @return AbstractBuilder
     */
    public function create($schema): AbstractBuilder
    {
        if (empty($schema->type) || empty($this->types[$schema->type])) {
            $exception = new UnknownTypeException('Unknown type of scheme');
            $exception
                 ->setSchema($schema);

            throw $exception;
        }

        $class = $this->types[$schema->type];

        /**
         * @var AbstractBuilder $builder
         */
        $builder = $this->container->make($class);

        $builder
            ->setSchema($schema);

        return $builder;
    }
}
