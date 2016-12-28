<?php
/**
 * @author Sergei Melnikov <me@rnr.name>
 */
use Mildberry\Specifications\Transforming\Resolvers;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules;

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
                    'remove' => Rules\RemoveRule::class,
                    'const' => Rules\ConstRule::class,
                    'shiftFrom' => Rules\ShiftFromRule::class,
                    'shiftTo' => Rules\ShiftToRule::class,
                ],
                'schema' => 'transform://transformations',
            ],
        ],
    ],
];
