<?php

namespace Mildberry\Specifications\Support\Testing;

use Mildberry\Specifications\Support\DataPreparator;
use Nelmio\Alice\Loader\NativeLoader;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
trait FixtureLoaderTrait
{
    /**
     * @param string $fn
     *
     * @return array
     */
    public function loadArray(string $fn)
    {
        $parser = new Yaml();

        return $parser->parse(file_get_contents($this->getFixtureFilename($fn)));
    }

    /**
     * @param string $fn
     *
     * @return object
     */
    public function loadObject(string $fn)
    {
        $preparator = new DataPreparator();

        return $preparator->prepare($this->loadArray($fn));
    }

    /**
     * @param string|array $fn
     *
     * @return array
     */
    public function loadEntities($fn)
    {
        $loader = new NativeLoader();

        /**
         * @var array $objects
         */
        $objects = array_reduce((is_array($fn)) ? ($fn) : ([$fn]), function ($entities, string $fn) use ($loader) {
            return $loader->loadFile($this->getFixtureFilename($fn), [], $entities)->getObjects();
        }, []);

        return $objects;
    }

    /**
     * @param string $fn
     *
     * @return string
     */
    abstract protected function getFixtureFilename(string $fn): string;
}
