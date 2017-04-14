<?php

namespace TreeSoft\Specifications\DAL\Eloquent\Appliers;

use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;
use TreeSoft\Specifications\Core\Specifications\Filters\IdSpecification;
use TreeSoft\Specifications\Core\Specifications\SpecificationInterface;

/**
 * Class ApplierFactory.
 */
class ApplierFactory implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var array
     */
    private $appliers = [
        IdSpecification::class => IdSpecificationApplier::class,
    ];

    /**
     * @param SpecificationInterface $specification
     *
     * @return AbstractApplier
     */
    public function create(SpecificationInterface $specification): AbstractApplier
    {
        $applier = $this->appliers[get_class($specification)];

        return is_string($applier) ? ($this->container->make($this->appliers[get_class($specification)])) : ($applier);
    }

    /**
     * @param string $specification
     * @param string|object $applier
     */
    public function register(string $specification, $applier)
    {
        $this->appliers[$specification] = $applier;
    }
}
