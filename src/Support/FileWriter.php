<?php

namespace TreeSoft\Specifications\Support;

use TreeSoft\Specifications\Generators\OutputInterface;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class FileWriter implements OutputInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @param string $path
     * @param string $content
     */
    public function write(string $path, string $content)
    {
        $filename = $this->join([$this->path, $path]);
        $directory = dirname($filename);

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        file_put_contents($this->join([$this->path, $path]), $content);
    }

    /**
     * @param string $path
     * @param string $content
     *
     * @return bool
     */
    public function isSaved(string $path, string $content): bool
    {
        return md5_file($this->join([$this->path, $path])) == md5($content);
    }

    /**
     * @param array $paths
     *
     * @return string
     */
    protected function join($paths = [])
    {
        return implode(DIRECTORY_SEPARATOR, $paths);
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
