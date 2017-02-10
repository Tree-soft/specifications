<?php

namespace Mildberry\Tests\Specifications\Mocks;

use League\JsonGuard\Loader as LoaderInterface;
use League\JsonGuard;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class LoaderMock implements LoaderInterface
{
    /**
     * @var array
     */
    private $schemaMap = [];

    /**
     * LoaderMock constructor.
     *
     * @param array $schemaMap
     */
    public function __construct($schemaMap = [])
    {
        $this->schemaMap = $schemaMap;
    }

    /**
     * @param string $path
     *
     * @return mixed
     */
    public function load($path)
    {
        return JsonGuard\json_decode(
            file_get_contents($this->schemaMap[$path])
        );
    }

    /**
     * @return array
     */
    public function getSchemaMap(): array
    {
        return $this->schemaMap;
    }

    /**
     * @param array $schemaMap
     *
     * @return $this
     */
    public function setSchemaMap(array $schemaMap)
    {
        $this->schemaMap = $schemaMap;

        return $this;
    }
}
