<?php

namespace Mildberry\Specifications\Schema;

use League\JsonGuard\Dereferencer;
use League\JsonGuard\Reference;
use League\JsonGuard\Validator;
use Mildberry\Specifications\Support\DatePreparator;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class Factory
{
    /**
     * @param string|object $schema
     *
     * @return object
     */
    public function schema($schema)
    {
        $dereferencer = $this->dereferencer();

        $data = $dereferencer->dereference($schema);

        return $this->resolveReferences($data);
    }

    /**
     * @return Dereferencer
     */
    public function dereferencer(): Dereferencer
    {
        $dereferencer = $this->createDereferencer();

        return $dereferencer;
    }

    /**
     * @return Dereferencer
     */
    protected function createDereferencer(): Dereferencer
    {
        return new Dereferencer();
    }

    /**
     * @param mixed $data
     * @param string|object $schema
     *
     * @return Validator
     */
    public function validator($data, $schema): Validator
    {
        $validator = $this->createValidator($this->prepareData($data), $schema);

        return $validator;
    }

    /**
     * @param mixed $data
     * @param string|object $schema
     *
     * @return Validator
     */
    protected function createValidator($data, $schema): Validator
    {
        return new Validator($data, $this->schema($schema));
    }

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    public function prepareData($data)
    {
        $preparator = new DatePreparator();

        return $preparator->prepare($data);
    }

    /**
     * @param mixed $schema
     *
     * @return mixed
     */
    public function resolveReferences($schema)
    {
        while ($schema instanceof Reference) {
            $schema = $schema->resolve();
        }

        if (is_object($schema) || is_array($schema)) {
            foreach ($schema as &$value) {
                $value = $this->resolveReferences($value);
            }
        }

        return $schema;
    }
}
