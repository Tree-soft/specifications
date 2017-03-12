<?php

namespace Mildberry\Specifications\Transforming\Converter;

use Mildberry\Specifications\Schema\LaravelFactory;
use Illuminate\Contracts\Config\Repository as Config;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class Extractor extends Converter
{
    /**
     * Extractor constructor.
     *
     * @param LaravelFactory $factory
     * @param Config $config
     */
    public function __construct(LaravelFactory $factory, Config $config)
    {
        parent::__construct($factory);

        $data = $config->get('specifications.extract');

        $this
            ->setResolvers($data['resolvers'] ?? []);
    }
}
