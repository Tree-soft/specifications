<?php

namespace Mildberry\Specifications\Transforming\Transformers;

use DeepCopy\DeepCopy;
use DeepCopy\Matcher\Matcher;
use DeepCopy\Matcher\PropertyNameMatcher;
use Mildberry\Specifications\Generators\TypeExtractor;
use Mildberry\Specifications\Schema\LaravelFactory;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules\AbstractRule;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules\ConstRule;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules\IgnoreRule;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules\PostRuleInterface;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules\RemoveRule;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules\ShiftFromRule;
use Mildberry\Specifications\Transforming\Transformers\JsonSchema\Rules\ShiftToRule;

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
    private $rules = [
        'ignore' => IgnoreRule::class,
        'remove' => RemoveRule::class,
        'const' => ConstRule::class,
        'shiftFrom' => ShiftFromRule::class,
        'shiftTo' => ShiftToRule::class,
    ];

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

        $copier = new DeepCopy();

        $rules = $this->applyFilters($copier, $from, $to);

        $fields = $this->getFields();

        if (isset($fields)) {
            $to = array_reduce(
                array_filter($rules, function ($filter) {
                    return $filter instanceof PostRuleInterface;
                }), function ($to, PostRuleInterface $rule) use ($fields) {
                    return array_reduce(
                        array_filter($fields, function ($field) use ($rule, $to) {
                            /**
                             * @var AbstractRule|PostRuleInterface $rule
                             */
                            return $rule->getMatcher()->matches($to, $field);
                        }),
                        function ($object, $field) use ($rule) {
                            return $rule->afterCopy($object, $field);
                        }, $to
                    );
                }, $copier->copy($from));
        }

        $this->hashMap[$hash] = $to;

        return $to;
    }

    /**
     * @param DeepCopy $copier
     * @param $from
     * @param mixed $to
     *
     * @return array
     */
    protected function applyFilters(DeepCopy $copier, $from, $to)
    {
        $rules = [];

        foreach ($this->transformation->rules as $property => $ruleDefinition) {
            $rule = $this->createRule($ruleDefinition);

            $rule
                ->setMatcher($this->createMatcher($property))
                ->setFrom($from)
                ->setTo($to);

            $rule->configure();

            $rule->apply($copier);

            $rules[] = $rule;
        }

        return $rules;
    }

    /**
     * @param string $ruleDefinition
     *
     * @return AbstractRule
     */
    protected function createRule(string $ruleDefinition): AbstractRule
    {
        list($name, $spec) = $this->parseSpecification($ruleDefinition);

        /**
         * @var AbstractRule $rule
         */
        $rule = $this->container->make($this->rules[$name]);

        $rule->setSpec($spec);

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
        /**
         * @var LaravelFactory $factory
         */
        $factory = $this->container->make(LaravelFactory::class);

        $schema = $factory->schema($this->transformation->to);

        $extractor = new TypeExtractor();
        $schema = $extractor->extendSchema($schema);

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

        return $this;
    }
}
