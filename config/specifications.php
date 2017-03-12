<?php
/**
 * @author Sergei Melnikov <me@rnr.name>
 */
use Mildberry\Specifications\Transforming\Resolvers;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;
use Mildberry\Specifications\Transforming\Transformers\SimpleType\Casters;
use Mildberry\Specifications\Transforming\Converter\Resolvers as ConverterResolvers;

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
        'resolvers' => [
            'nullable' => [
                'class' => ConverterResolvers\NullableResolver::class,
            ],
            'simple' => [
                'class' => ConverterResolvers\SimpleResolver::class,
            ],
            'object' => [
                'class' => ConverterResolvers\Populator\ObjectResolver::class,
            ],
        ],
    ],
    'extract' => [
        'resolvers' => [
            'nullable' => [
                'class' => ConverterResolvers\NullableResolver::class,
            ],
            'simple' => [
                'class' => ConverterResolvers\SimpleResolver::class,
            ],
            'object' => [
                'class' => ConverterResolvers\Extractor\ObjectResolver::class,
            ],
            'complex' => [
                'class' => ConverterResolvers\ComplexResolver::class,
            ],
        ],
    ],
];
