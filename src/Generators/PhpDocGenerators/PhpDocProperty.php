<?php

namespace TreeSoft\Specifications\Generators\PhpDocGenerators;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class PhpDocProperty extends AbstractGenerator
{
    /**
     * @var string
     */
    private $type;

    /**
     * @return string
     */
    public function compile(): string
    {
        return $this->loadTemplate('property', [
            'type' => $this->type,
        ]);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}
