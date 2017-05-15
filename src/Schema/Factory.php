<?php

namespace TreeSoft\Specifications\Schema;

use League\JsonGuard\Validator;
use League\JsonReference\Dereferencer;
use League\JsonReference\Reference;


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
        return Dereferencer::draft4();
    }

    /**
     * @param mixed $data
     * @param string|object $schema
     *
     * @return Validator
     */
    public function validator($data, $schema): Validator
    {
        $validator = $this->createValidator($data, $schema);

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
