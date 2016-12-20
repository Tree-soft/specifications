<?php

namespace Mildberry\Specifications\Schema;

use League\JsonGuard\Exceptions\SchemaLoadingException;
use League\JsonGuard\Loader as LoaderInterface;
use League\JsonGuard;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class Loader implements LoaderInterface
{
    private $path = '';

    /**
     * @param string $path
     *
     * @return mixed|object
     */
    public function load($path)
    {
        $path = $this->getFileName($path);

        if (!file_exists($path)) {
            throw SchemaLoadingException::notFound($path);
        }

        return JsonGuard\json_decode(file_get_contents($path), false, 512, JSON_BIGINT_AS_STRING);
    }

    public function getFileName($path)
    {
        $path = rtrim(JsonGuard\strip_fragment($path), '#');
        $paths = [$path];

        if (!empty($this->path)) {
            array_unshift($paths, $this->path);
        }

        $path = implode(DIRECTORY_SEPARATOR, $paths);
        $path = "{$path}.json";

        return $path;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function setPath(string $path)
    {
        $this->path = $path;

        return $this;
    }
}
