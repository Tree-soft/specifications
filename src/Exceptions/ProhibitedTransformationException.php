<?php

namespace Mildberry\Specifications\Exceptions;

use Exception;
use Mildberry\Specifications\Generators\ClassBuilders\TypeExtractor;
use Rnr\Resolvers\Interfaces\ContainerAwareInterface;
use Rnr\Resolvers\Traits\ContainerAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class ProhibitedTransformationException extends Exception implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    const UNKNOWN_SCHEMA = 'unknown schema name';

    /**
     * ProhibitedTransformationException constructor.
     */
    public function __construct()
    {
        parent::__construct('', 0, null);
    }

    /**
     * @var mixed
     */
    private $from;

    /**
     * @var mixed
     */
    private $to;

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param mixed $from
     *
     * @return $this
     */
    public function setFrom($from)
    {
        $this->from = $from;

        $this->message = (string) $this;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param mixed $to
     *
     * @return $this
     */
    public function setTo($to)
    {
        $this->to = $to;

        $this->message = (string) $this;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $from = $this->wrapSchemaName(
            $this->getSchemaName($this->from)
        );

        $to = $this->wrapSchemaName(
            $this->getSchemaName($this->to)
        );

        return "Transformation from {$from} to {$to} is prohibited";
    }

    /**
     * @param string|object|mixed $schema
     *
     * @return string|null
     */
    public function getSchemaName($schema): ?string
    {
        if (is_string($schema)) {
            return $schema;
        } elseif (is_object($schema)) {
            return $schema->id ?? $schema->type ?? null;
        }

        return null;
    }

    /**
     * @param $schema
     *
     * @return string
     */
    public function wrapSchemaName($schema): string
    {
        if (is_null($schema)) {
            return self::UNKNOWN_SCHEMA;
        }

        /**
         * @var TypeExtractor $typeExtractor
         */
        $typeExtractor = $this->container->make(TypeExtractor::class);

        return ($typeExtractor->isSchema($schema)) ? ("'{$schema}'") : ("type '{$schema}'");
    }
}
