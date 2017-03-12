<?php

namespace Mildberry\Specifications\Transforming\Converter;

use Mildberry\Specifications\Schema\LaravelFactory;
use Illuminate\Contracts\Config\Repository as Config;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class Populator extends Converter
{
    /**
     * Populator constructor.
     *
     * @param LaravelFactory $factory
     * @param Config $config
     */
    public function __construct(LaravelFactory $factory, Config $config)
    {
        parent::__construct($factory);

        $data = $config->get('specifications.populate');
        $this
            ->setResolvers($data['resolvers'] ?? []);
    }
}
