<?php

namespace TreeSoft\Specifications\Support;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
trait PublishDataTrait
{
    /**
     * @return array
     */
    public function getPublishingData()
    {
        return [];
    }

    /**
     * Register paths to be published by the publish command.
     *
     * @param  array  $paths
     * @param  string  $group
     *
     * @return void
     */
    abstract protected function publishes(array $paths, $group = null);

    protected function publishData()
    {
        foreach ($this->getPublishingData() as $tag => $data) {
            $this->publishes($data, $tag);
        }
    }
}
