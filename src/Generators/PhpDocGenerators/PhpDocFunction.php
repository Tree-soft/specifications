<?php

namespace TreeSoft\Specifications\Generators\PhpDocGenerators;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class PhpDocFunction extends AbstractGenerator
{
    /**
     * @var array
     */
    private $params = [];

    /**
     * @var string|array
     */
    private $returnType;

    /**
     * @return string
     */
    public function compile(): string
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
        $this->params = $params;

        return $this;
    }

    /**
     * @return string|array
     */
    public function getReturnType()
    {
        return $this->returnType;
    }

    /**
     * @param string|array $returnType
     *
     * @return $this
     */
    public function setReturnType($returnType)
    {
        $this->returnType = $returnType;

        return $this;
    }
}
