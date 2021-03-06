<?php

namespace TreeSoft\Specifications\Transforming\Transformers\JsonSchema\Transformations;

use TreeSoft\Specifications\Transforming\TransformerFactory;
use TreeSoft\Specifications\Transforming\Transformers\ValueDescriptor;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
abstract class AbstractTransformation implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @param array $args
     * @param callable $next
     *
     * @return mixed
     */
    public function handle($args, $next)
    {
        list($from, $value) = $args;

        return $this->apply($from, $value, function ($from, $value) use ($next) {
            return $next([$from, $value]);
        });
    }

    /**
     * @param ValueDescriptor $from
     * @param ValueDescriptor $value
     * @param callable $next
     *
     * @return mixed
     */
    abstract public function apply(ValueDescriptor $from, ValueDescriptor $value, $next): ValueDescriptor;

    /**
     * @param array $config
     */
    public function configure(array $config)
    {
    }

    /**
     * @param ValueDescriptor $from
     * @param ValueDescriptor $to
     *
     * @return ValueDescriptor
     */
    public function transform(ValueDescriptor $from, ValueDescriptor $to): ValueDescriptor
    {
        /**
         * @var TransformerFactory $factory
         */
        $factory = $this->container->make(TransformerFactory::class);

        $transformer = $factory->create($from->getSchema(), $to->getSchema());

        $new = new ValueDescriptor();

        $new
            ->setSchema($to->getSchema())
            ->setValue($transformer->transform($from->getValue(), $to->getValue()));

        return $new;
    }
}
