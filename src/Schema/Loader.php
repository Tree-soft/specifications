<?php

namespace TreeSoft\Specifications\Schema;

use League\JsonReference\LoaderInterface;
use League\JsonReference;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class Loader implements LoaderInterface
{
    /**
     * @var string
     */
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
            throw JsonReference\SchemaLoadingException::notFound($path);
        }

        return json_decode(file_get_contents($path), false, 512, JSON_BIGINT_AS_STRING);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function getFileName(string $path): string
    {
        $path = rtrim(JsonReference\strip_fragment($path), '#');
        $paths = [$path];

        if (!empty($this->path)) {
            array_unshift($paths, $this->path);
        }

        $path = implode(DIRECTORY_SEPARATOR, $paths);
        return "{$path}.json";
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
