<?php

namespace Mildberry\Specifications\Generators\PhpDocGenerators;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class PhpDocProperty extends AbstractGenerator
{
    use ObjectTypeTrait;

    /**
     * @var string
     */
    private $type;

    public function __toString()
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
    public function setType(string $type)
    {
        $this->type = $this->convertType($type);

        return $this;
    }
}
