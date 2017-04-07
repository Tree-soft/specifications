<?php

namespace TreeSoft\Specifications\Generators;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
interface OutputInterface
{
    /**
     * @param string $path
     * @param string $content
     */
    public function write(string $path, string $content);

    /**
     * @param string $path
     * @param string $content
     *
     * @return bool
     */
    public function isSaved(string $path, string $content): bool;
}
