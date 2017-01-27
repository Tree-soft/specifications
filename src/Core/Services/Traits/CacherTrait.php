<?php

namespace Mildberry\Specifications\Core\Services\Traits;

use Mildberry\Specifications\Core\Interfaces\CacheInterface;
use Throwable;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
trait CacherTrait
{
    /**
     * @var CacheInterface
     */
    private $cacher;

    /**
     * @return string
     */
    abstract public function getKey(): string;

    /**
     * @throws Throwable
     *
     * @return mixed
     */
    protected function executeWithCaching()
    {
        $key = $this->keyByObject($this, $this->getKey());

        if ($this->cacher->has($key)) {
            return $this->cacher->get($key);
        }

        $result = parent::execute();

        $this->cacher->set($key, $result);

        return $result;
    }

    /**
     * @param mixed $object
     * @param string $key
     *
     * @return string
     */
    public function keyByObject($object, string $key): string
    {
        return sha1(get_class($object) . $key);
    }
}
