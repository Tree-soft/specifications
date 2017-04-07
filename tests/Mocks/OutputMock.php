<?php

namespace TreeSoft\Tests\Specifications\Mocks;

use TreeSoft\Specifications\Generators\OutputInterface;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class OutputMock implements OutputInterface
{
    /**
     * @var array
     */
    private $files = [];

    /**
     * @param string $path
     * @param string $content
     */
    public function write(string $path, string $content)
    {
        $this->files[$path] = $content;
    }

    /**
     * @return array
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @param array $files
     *
     * @return $this
     */
    public function setFiles(array $files)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * @param string $path
     * @param string $content
     *
     * @return bool
     */
    public function isSaved(string $path, string $content): bool
    {
        return array_key_exists($path, $this->files) && ($this->files == $content);
    }
}
