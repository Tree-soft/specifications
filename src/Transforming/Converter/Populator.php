<?php

namespace Mildberry\Specifications\Transforming\Converter;

use Mildberry\Specifications\Generators\TypeExtractor;
use Mildberry\Specifications\Schema\LaravelFactory;
use Exception;
use Illuminate\Contracts\Config\Repository as Config;
use Mildberry\Specifications\Transforming\Converter\Fillers\SetterFiller;

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
            ->setFiller($data['filler'] ?? SetterFiller::class)
            ->setResolvers($data['resolvers'] ?? []);
    }

    /**
     * @param mixed $data
     * @param object $schema
     *
     * @throws Exception
     *
     * @return mixed
     */
    public function createEntity($schema, $data)
    {
        $typeExtractor = new TypeExtractor();

        $typeExtractor
            ->setNamespace($this->namespace);

        $types = $typeExtractor->extract($schema);

        if (!is_array($types)) {
            $class = $types;
        } else {
            throw new Exception('Polymorphic types is not implemented.');
        }

        return new $class();
    }
}
