<?php
/**
 * @author Sergei Melnikov <me@rnr.name>
 */
use Mildberry\Specifications\Transforming\Resolvers;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;
use Mildberry\Specifications\Transforming\Transformers\SimpleType\Casters;

return [
    'path' => dirname(__DIR__) . '/resources/schema',
    'transform' => [
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
];
