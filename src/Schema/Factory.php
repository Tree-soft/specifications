<?php

namespace Mildberry\Specifications\Schema;

use League\JsonGuard\Dereferencer;
use League\JsonGuard\Validator;

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

        return $dereferencer->dereference($schema);
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
        $validator = $this->createValidator($data, $schema);

        return $validator;
    }

    protected function createValidator($data, $schema): Validator
    {
        return new Validator($data, $this->schema($schema));
    }
}
