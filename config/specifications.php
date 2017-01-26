<?php
/**
 * @author Sergei Melnikov <me@rnr.name>
 */
use Mildberry\Specifications\Transforming\Resolvers;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;
use Mildberry\Specifications\Transforming\Transformers\SimpleType\Casters;
use Mildberry\Specifications\Transforming\Converter\Resolvers\Populator as PopulatorResolvers;
use Mildberry\Specifications\Transforming\Converter\Resolvers\Extractor as ExtractorResolvers;
use Mildberry\Specifications\Transforming\Converter\Fillers;

return [
    'path' => dirname(__DIR__) . '/resources/schema',
    'namespace' => '\App\Core',
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
        'filler' => Fillers\SetterFiller::class,
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
    'extract' => [
        'filler' => Fillers\ObjectFiller::class,
        'resolvers' => [
            'nullable' => [
                'class' => ExtractorResolvers\NullableResolver::class,
            ],
            'simple' => [
                'class' => ExtractorResolvers\SimpleResolver::class,
            ],
            'object' => [
                'class' => ExtractorResolvers\ObjectResolver::class,
            ],
        ],
    ],
];
