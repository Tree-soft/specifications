<?php

namespace TreeSoft\Tests\Specifications\Support;

use TreeSoft\Specifications\Support\PublisherInterface;
use ArrayAccess;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
trait PublishedDataAssertionTrait
{
    /**
     * @param string $filename
     * @param string $message
     */
    abstract public static function assertFileExists($filename, $message = '');

    /**
     * Asserts that an array has a specified key.
     *
     * @param mixed             $key
     * @param array|ArrayAccess $array
     * @param string            $message
     *
     * @since Method available since Release 3.0.0
     */
    abstract public static function assertArrayHasKey($key, $array, $message = '');

    /**
     * @param PublisherInterface $provider
     */
    public function assertPublishData(PublisherInterface $provider)
    {
        $data = $provider->getPublishingData();

        $this->assertArrayHasKey('config', $data);

        foreach (array_keys($data['config']) as $path) {
            $this->assertFileExists($path);
        }

        $this->assertArrayHasKey('schema', $data);

        foreach (array_keys($data['schema']) as $path) {
            $this->assertFileExists($path);
        }
    }
}
