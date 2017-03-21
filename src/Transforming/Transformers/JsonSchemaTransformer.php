<?php

namespace Mildberry\Specifications\Transforming\Transformers;

use Illuminate\Pipeline\Pipeline;
use Mildberry\Specifications\Generators\TypeExtractor;
use Mildberry\Specifications\Schema\LaravelFactory;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rule;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Transformations\ValueExtractor;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Transformations\ValuePopulator;
use RuntimeException;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class JsonSchemaTransformer extends AbstractTransformer
{
    /**
     * @var object
     */
    private $transformation;

    /**
     * @var array
     */
    private $hashMap = [];

    /**
     * @var array
     */
    private $rules = [];

    /**
     * @var LaravelFactory
     */
    private $factory;

    /**
     * @var TypeExtractor
     */
    private $extractor;

    /**
     * @var object $fromSchema
     */
    private $fromSchema;

    /**
     * @var object $toSchema
     */
    private $toSchema;

    /**
     * JsonSchemaTransformer constructor.
     *
     * @param LaravelFactory $factory
     * @param TypeExtractor $extractor
     */
    public function __construct(LaravelFactory $factory, TypeExtractor $extractor)
    {
        $this->factory = $factory;
        $this->extractor = $extractor;
    }

    /**
     * @param mixed $from
     * @param mixed|null $to
     *
     * @return mixed
     */
    public function transform($from, $to = null)
    {
        if (is_object($from)) {
            $hash = spl_object_hash($from);

            if (isset($this->hashMap[$hash])) {
                return $this->hashMap[$hash];
            }
        }

        $rulesDefinitions = (array) ($this->transformation->rules ?? []);

        $rules = array_map(function (string $definition) {
            return $this->parseRule($definition);
        }, $rulesDefinitions);

        $rules = $this->fillCopyRule($rules);

        $value = $this->applyRules($rules, $from, $to);

        if (is_object($from)) {
            $hash = spl_object_hash($from);

            $this->hashMap[$hash] = $value;
        }

        return $value;
    }

    /**
     * @param string $string
     *
     * @return Rule
     */
    public function parseRule(string $string): Rule
    {
        $rule = new Rule();

        $rule
            ->setFrom(ValueExtractor::RETURN_SELF)
            ->setTransformations([])
            ->setTo(null);

        return $rule;
    }

    /**
     * @param array $rules
     *
     * @return array
     */
    public function fillCopyRule(array $rules): array
    {
        if ($this->fromSchema->type !== TypeExtractor::OBJECT) {
            if (empty($rules)) {
                $rules[] = new Rule();
            }

            return $rules;
        }

        $fieldsInRules = array_unique(
            array_map(
                function (Rule $rule) {
                    return $rule->getFrom();
                }, $rules
            )
        );

        $fields = array_intersect(
            array_keys((array) $this->fromSchema->properties),
            array_keys((array) $this->toSchema->properties)
        );

        $copyingFields = array_filter(
            $fields,
            function (string $field) use ($fieldsInRules) {
                return !in_array($field, $fieldsInRules);
            }
        );

        foreach ($copyingFields as $field) {
            $rule = new Rule();

            $rule
                ->setFrom($field)
                ->setTo($field);

            $rules[] = $rule;
        }

        return $rules;
    }

    /**
     * @param array|Rule[] $rules
     * @param mixed $from
     * @param mixed $to
     *
     * @return mixed
     */
    protected function applyRules(array $rules, $from, $to = null)
    {
        if (is_object($from)) {
            $value = isset($to) ? (clone $to) : ((object) []);
        } else {
            $value = $to;
        }

        return array_reduce($rules, function ($value, Rule $rule) use ($from) {
            $pipeline = new Pipeline();

            /**
             * @var ValueExtractor $valueExtractor
             */
            $valueExtractor = $this->container->make(ValueExtractor::class);

            $valueExtractor
                ->setField($rule->getFrom());

            /**
             * @var ValuePopulator $valuePopulator
             */
            $valuePopulator = $this->container->make(ValuePopulator::class);

            $valuePopulator
                ->setField($rule->getTo());

            $transformations = array_merge([$valueExtractor], $rule->getTransformations(), [$valuePopulator]);

            $fromDescriptor = new ValueDescriptor();

            $fromDescriptor
                ->setValue($from)
                ->setSchema($this->fromSchema);

            $valueDescriptor = new ValueDescriptor();

            $valueDescriptor
                ->setValue($value)
                ->setSchema($this->toSchema);

            /**
             * @var ValueDescriptor $valueDescriptor
             */
            $valueDescriptor = $pipeline
                ->send([$fromDescriptor, $valueDescriptor])
                ->through($transformations)
                ->then(function () {
                    throw new RuntimeException('There code should not be executed');
                });

            return $valueDescriptor->getValue();
        }, $value);
    }

    /**
     * @param string $ruleDefinition
     *
     * @return array
     */
    protected function parseSpecification(string $ruleDefinition)
    {
        $parts = explode(':', $ruleDefinition);

        $name = $parts[0];

        $spec = (count($parts) > 1) ? (explode(',', $parts[1])) : ([]);

        return [$name, $spec];
    }

    /**
     * @return object
     */
    public function getTransformation()
    {
        return $this->transformation;
    }

    /**
     * @param object $transformation
     *
     * @return $this
     */
    public function setTransformation($transformation)
    {
        $this->transformation = $transformation;

        $this->fromSchema = $this->extractor->extendSchema(
            $this->factory->schema($this->transformation->from)
        );

        $this->toSchema = $this->extractor->extendSchema(
            $this->factory->schema($this->transformation->to)
        );

        return $this;
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return $this->rules;
    }
}
