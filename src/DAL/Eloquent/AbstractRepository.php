<?php

namespace TreeSoft\Specifications\DAL\Eloquent;

use TreeSoft\Specifications\Core\Interfaces\RepositoryInterface;
use TreeSoft\Specifications\DAL\Transformers\EntityTransformerFactory;
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
        assert(is_a($this->model, Model::class, true), 'Class should be derived from ' . Model::class);

        $this->factory = $factory;
    }

    /**
     * @param mixed $queryOptions
     *
     * @return array
     */
    public function findBy($queryOptions)
    {
        // TODO: Implement findBy() method.
        return [];
    }

    /**
     * @param mixed $queryOptions
     *
     * @return mixed
     */
    public function findOneBy($queryOptions)
    {
        // TODO: Implement findOneBy() method.
        return null;
    }

    /**
     * @param mixed $entity
     * @param mixed|null $queryOptions
     *
     * @return mixed
     */
    public function insert($entity, $queryOptions = null)
    {
        $transformer = $this->factory->create(get_class($entity));

        $model = $this->container->make($this->model);
        $model = $transformer->extract($entity, $model);
        $model->save();

        return $transformer->populate($model);
    }

    /**
     * @param mixed $entity
     * @param mixed|null $queryOptions
     *
     * @return mixed
     */
    public function update($entity, $queryOptions = null)
    {
        // TODO: Implement update() method.
        return null;
    }

    /**
     * @param mixed $queryOptions
     */
    public function updateBy($queryOptions)
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
     * @param mixed $queryOptions
     */
    public function deleteBy($queryOptions)
    {
        // TODO: Implement deleteBy() method.
    }
}
