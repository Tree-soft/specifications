<?php
/**
 * @author Sergei Melnikov <me@rnr.name>
 */
use Mildberry\Specifications\Transforming\Resolvers;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;
use Mildberry\Specifications\Transforming\Transformers\SimpleType\Casters;
use Mildberry\Specifications\Transforming\Populator\Fillers\SetterFiller;
use Mildberry\Specifications\Transforming\Populator\Resolvers as PopulatorResolvers;

return [
    'path' => dirname(__DIR__) . '/resources/schema',
    'namespace' => '\Core',
    'transform' => [
        'path' => dirname(__DIR__) . '/resources/transform',
        'resolvers' => [
            'equal' => [
                'class' => Resolvers\CopyResolver::class,
            ],
            'json-schema' => [
                'class' => Resolvers\JsonSchemaResolver::class,
                'rules' => [
                    'ignore' => Rules\IgnoreRule::class,
                    'const' => Rules\ConstRule::class,
                    'shiftFrom' => Rules\ShiftFromRule::class,
                    'shiftTo' => Rules\ShiftToRule::class,
                ],
                'schema' => 'transform://transformations',
            ],
            'simple' => [
                'class' => Resolvers\SimpleTypeResolver::class,
                'casters' => [
                    'boolean' => Casters\BooleanCaster::class,
                    'number' => Casters\FloatCaster::class,
                    'string' => Casters\StringCaster::class,
                    'integer' => Casters\IntegerCaster::class,
                ],
            ],
        ],
    ],
    'populate' => [
        'filler' => SetterFiller::class,
        'resolvers' => [
            'nullable' => [
                'class' => PopulatorResolvers\NullableResolver::class,
            ],
            'simple' => [
                'class' => PopulatorResolvers\SimpleResolver::class,
            ],
            'object' => [
                'class' => PopulatorResolvers\ObjectResolver::class,
            ],
        ],
    ],
];
