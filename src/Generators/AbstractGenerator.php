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
}
