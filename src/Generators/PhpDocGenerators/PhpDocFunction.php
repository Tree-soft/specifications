<?php

namespace Mildberry\Specifications\Generators\PhpDocGenerators;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class PhpDocFunction extends AbstractGenerator
{
    use ObjectTypeTrait;

    /**
     * @var array
     */
    private $params = [];

    /**
     * @var string
     */
    private $returnType;

    public function __toString()
    {
        return $this->loadTemplate('function', [
            'params' => $this->params,
            'returnType' => $this->returnType,
        ]);
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     *
     * @return $this
     */
    public function setParams(array $params)
    {
        $this->params = array_map(function ($type) {
            return $this->convertType($type);
        }, $params);

        return $this;
    }

    /**
     * @return string
     */
    public function getReturnType(): string
    {
        return $this->returnType;
    }

    /**
     * @param string $returnType
     *
     * @return $this
     */
    public function setReturnType(string $returnType)
    {
        $this->returnType = $this->convertType($returnType);

        return $this;
    }
}
