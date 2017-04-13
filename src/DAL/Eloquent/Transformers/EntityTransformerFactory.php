<?php

namespace TreeSoft\Specifications\DAL\Eloquent\Transformers;

use TreeSoft\Specifications\DAL\Eloquent\Model;
use TreeSoft\Specifications\Support\Transformers\FactoryCreatorTrait;
use Rnr\Resolvers\Interfaces\ConfigAwareInterface;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ConfigAwareTrait;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class EntityTransformerFactory implements ConfigAwareInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;
    use ConfigAwareTrait;
    use FactoryCreatorTrait;

    /**
     * @var array|TransformerBuilderInterface[]|string[]
     */
    private $builders = [];

    /**
     * @param string $class
     *
     * @return EntityTransformer
     */
    public function createByEntity(string $class): EntityTransformer
    {
        /**
         * @var EntityTransformer $transformer
         */
        $transformer = $this->container->make(EntityTransformer::class);

        $this->fillTransformer($class, $transformer);

        return $transformer;
    }

    /**
     * @param Model $model
     *
     * @return EntityTransformer
     */
    public function createByModel(Model $model): EntityTransformer
    {
        $builder = $this->builders[get_class($model)];

        return $this->createByEntity(
            is_string($builder) ? $builder : $builder->getClass($model)
        );
    }

    /**
     * @return array|TransformerBuilderInterface[]
     */
    public function getBuilders()
    {
        return $this->builders;
    }

    /**
     * @param array|TransformerBuilderInterface[] $builders
     *
     * @return $this
     */
    public function setBuilders($builders)
    {
        $this->builders = $builders;

        return $this;
    }
}
