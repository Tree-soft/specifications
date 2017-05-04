<?php

namespace TreeSoft\Specifications\DAL\Eloquent;

use TreeSoft\Specifications\Core\Interfaces\RepositoryInterface;
use TreeSoft\Specifications\Core\Specifications\DummySpecification;
use TreeSoft\Specifications\Core\Specifications\Filters\IdSpecification;
use TreeSoft\Specifications\Core\Specifications\SpecificationInterface;
use TreeSoft\Specifications\DAL\Eloquent\Transformers\EntityTransformerFactory;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;
use TreeSoft\Tests\Specifications\Mocks\Dal\Models\ModelMock;

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
     * @param SpecificationInterface $specification
     *
     * @return array
     */
    public function findBy(SpecificationInterface $specification)
    {
        /**
         * @var QueryBuilder $builder
         */
        $builder = $this->container->make(QueryBuilder::class);

        return $builder->result($this->container->make($this->model), $specification)->map(function (Model $model) {
            $transformer = $this->factory->createByModel($model);

            return $transformer->populate($model);
        })->toArray();
    }

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->findBy(new DummySpecification());
    }

    /**
     * @param SpecificationInterface $specification
     *
     * @return mixed
     */
    public function findOneBy(SpecificationInterface $specification)
    {
        return $this->findBy($specification)[0] ?? null;
    }

    /**
     * @param mixed $entity
     * @param SpecificationInterface|null $specification
     *
     * @return mixed
     */
    public function insert($entity, SpecificationInterface $specification = null)
    {
        $model = $this->container->make($this->model);

        return $this->save($model, $entity, $specification);
    }

    /**
     * @param mixed $entity
     * @param SpecificationInterface|null $specification
     *
     * @return mixed
     */
    public function update($entity, SpecificationInterface $specification = null)
    {
        /**
         * @var ModelMock $model
         */
        $model = $this->container->make($this->model);
        $model->exists = true;

        return $this->save($model, $entity, $specification);
    }

    /**
     * @param Model $model
     * @param mixed $entity
     * @param SpecificationInterface|null $specification
     *
     * @return mixed
     */
    public function save(Model $model, $entity, SpecificationInterface $specification = null)
    {
        $transformer = $this->factory->createByEntity(get_class($entity));
        $model = $transformer->extract($entity, $model);
        $model->save();

        return $transformer->populate($model);
    }

    /**
     * @param SpecificationInterface $specification
     */
    public function updateBy(SpecificationInterface $specification)
    {
        // TODO: Implement updateBy() method.
    }

    /**
     * @param mixed $entity
     * @param SpecificationInterface|null $specification
     */
    public function delete($entity, SpecificationInterface $specification = null)
    {
        $transformer = $this->factory->createByEntity(get_class($entity));
        $model = $transformer->extract($entity, $this->container->make($this->model));

        $model->delete();
    }

    /**
     * @param SpecificationInterface $specification
     */
    public function deleteBy(SpecificationInterface $specification)
    {
        /**
         * @var QueryBuilder $builder
         */
        $builder = $this->container->make(QueryBuilder::class);

        $builder->build(
            $this->container->make($this->model), $specification
        )->delete();
    }

    /**
     * @param SpecificationInterface $specification
     *
     * @return bool
     */
    public function exists(SpecificationInterface $specification): bool
    {
        return !is_null($this->findOneBy($specification));
    }
}
