<?php

namespace TreeSoft\Specifications\DAL\Eloquent;

use TreeSoft\Specifications\Core\Interfaces\RepositoryInterface;
use TreeSoft\Specifications\DAL\Eloquent\Transformers\EntityTransformerFactory;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class AbstractRepository implements RepositoryInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var string
     */
    protected $model;

    /**
     * @var EntityTransformerFactory
     */
    protected $factory;

    /**
     * AbstractEloquentRepository constructor.
     *
     * @param EntityTransformerFactory $factory
     */
    public function __construct(EntityTransformerFactory $factory)
    {
        assert(isset($this->model), 'Set up a class of model');
        assert(
            is_a($this->model, Model::class, true),
            'Class should be derived from ' . Model::class
        );

        $this->factory = $factory;
    }

    /**
     * @param mixed $expression
     *
     * @return array
     */
    public function findBy($expression)
    {
        /**
         * @var QueryBuilder $builder
         */
        $builder = $this->container->make(QueryBuilder::class);

        return $builder->result($this->container->make($this->model), $expression)->map(function (Model $model) {
            $transformer = $this->factory->createByModel($model);

            return $transformer->populate($model);
        })->toArray();
    }

    /**
     * @param mixed $expression
     *
     * @return mixed
     */
    public function findOneBy($expression)
    {
        return $this->findBy($expression)[0] ?? null;
    }

    /**
     * @param mixed $entity
     * @param mixed|null $expression
     *
     * @return mixed
     */
    public function insert($entity, $expression = null)
    {
        $transformer = $this->factory->createByEntity(get_class($entity));

        $model = $this->container->make($this->model);
        $model = $transformer->extract($entity, $model);
        $model->save();

        return $transformer->populate($model);
    }

    /**
     * @param mixed $entity
     * @param mixed|null $expression
     *
     * @return mixed
     */
    public function update($entity, $expression = null)
    {
        // TODO: Implement update() method.
        return null;
    }

    /**
     * @param mixed $expression
     */
    public function updateBy($expression)
    {
        // TODO: Implement updateBy() method.
    }

    /**
     * @param mixed $entity
     */
    public function delete($entity)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param mixed $expression
     */
    public function deleteBy($expression)
    {
        // TODO: Implement deleteBy() method.
    }

    /**
     * @param $expression
     *
     * @return bool
     */
    public function exists($expression): bool
    {
        return !is_null($this->findOneBy($expression));
    }
}
