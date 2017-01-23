<?php

namespace Mildberry\Specifications\Transforming\Transformers;

use Mildberry\Specifications\Generators\TypeExtractor;
use Mildberry\Specifications\Schema\LaravelFactory;
use Mildberry\Specifications\Support\DeepCopy\Matchers\SchemaMatcher;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Matcher\PropertyNameMatcher;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Matcher\SuccessMatcher;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules\AbstractRule;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules\CopyRule;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules\RuleFromInterface;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules\RuleToInterface;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules\SchemaRule;

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
     * @param LaravelFactory $factory
     */
    public function __construct(LaravelFactory $factory)
    {
        $this->factory = $factory;
        $this->extractor = new TypeExtractor();
    }


    /**
     * @param mixed $from
     * @param mixed|null $to
     *
     * @return mixed
     */
    public function transform($from, $to = null)
    {
        $to = $to ?? (object) [];

        $hash = spl_object_hash($from);

        if (isset($this->hashMap[$hash])) {
            return $this->hashMap[$hash];
        }

        $rules = $this->createFilters($from, $to);

        $object = clone $to;

        $object = $this->applyFilters($rules['from'], (array)$this->fromSchema->properties, $object);
        $object = $this->applyFilters($rules['to'], (array)$this->toSchema->properties, $object);

        $this->hashMap[$hash] = $object;

        return $object;
    }

    /**
     * @param array $rules
     * @param array $properties
     * @param mixed $object
     *
     * @return mixed
     */
    protected function applyFilters(array $rules, array $properties, $object) {
        foreach ($properties as $property => $spec) {
            $object = array_reduce($rules,
                function ($object, AbstractRule $rule) use ($property, $spec) {
                    return $rule->apply($property, $spec, $object);
                }, $object
            );
        }

        return $object;
    }
    /**
     * @param mixed $from
     * @param mixed $to
     *
     * @return array|AbstractRule[][]
     */
    protected function createFilters($from, $to)
    {
        $rulesDefinitions = (array) $this->transformation->rules;

        /**
         * @var AbstractRule[] $rules
         */
        $rules = array_map(
            [$this, 'createRule'],
            array_keys($rulesDefinitions),
            $rulesDefinitions
        );

        array_unshift($rules, $this->createCopyRule());

        foreach ($rules as $rule) {
            $rule
                ->setFrom($from)
                ->setTo($to);
        }

        return [
            'from' => array_filter($rules, function ($rule) {
                return $rule instanceof RuleFromInterface;
            }),
            'to' => array_filter($rules, function ($rule) {
                return $rule instanceof  RuleToInterface;
            })
        ];
    }

    /**
     * @param string $property
     * @param string $ruleDefinition
     *
     * @return AbstractRule
     */
    protected function createRule(string $property, string $ruleDefinition): AbstractRule
    {
        list($name, $spec) = $this->parseSpecification($ruleDefinition);

        /**
         * @var AbstractRule $rule
         */
        $rule = $this->container->make($this->rules[$name]);

        /**
         * @var PropertyNameMatcher $matcher
         */
        $matcher = $this->container->make(PropertyNameMatcher::class);
        $matcher
            ->setName($property);

        $rule
            ->setSpec($spec)
            ->setMatcher($matcher);

        return $rule;
    }

    /**
     * @return CopyRule
     */
    protected function createCopyRule(): CopyRule {
        /**
         * @var CopyRule $rule
         */
        $rule = $this->container->make(CopyRule::class);

        $rule
            ->setMatcher($this->container->make(SuccessMatcher::class));

        return $rule;
    }

    /**
     * @param $from
     * @param $to
     *
     * @return SchemaRule
     */
    protected function createSchemaRule($from, $to): SchemaRule {
        /**
         * @var SchemaRule $rule
         */
        $rule = $this->container->make(SchemaRule::class);

        $matcher = new SchemaMatcher();

        $schemaFrom = $this->factory->schema($this->transformation->to);
        $schemaTo = $this->factory->schema($this->transformation->from);

        $matcher
            ->setFrom($from)
            ->setTo($to)
            ->setSchemaTo($schemaTo)
            ->setSchemaFrom($schemaFrom);

        $rule
            ->setMatcher($matcher)
            ->setSchemaTo($schemaTo)
            ->setSchemaFrom($schemaFrom);

        return $rule;
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
     * @return array
     */
    protected function getFields(): array
    {
        $schema = $this->factory->schema($this->transformation->to);

        $schema = $this->extractor->extendSchema($schema);

        $properties = (array) ($schema->properties ?? []);

        return array_keys($properties);
    }

    /**
     * @param string $property
     *
     * @return Matcher
     */
    protected function createMatcher(string $property): Matcher
    {
        return new PropertyNameMatcher($property);
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

    /**
     * @param array $rules
     *
     * @return $this
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;

        return $this;
    }
}
