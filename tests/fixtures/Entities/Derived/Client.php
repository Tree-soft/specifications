<?php

namespace Mildberry\Tests\Specifications\Fixtures\Entities\Derived;

use Mildberry\Tests\Specifications\Fixtures\Entities\Client as ParentClient;

/**
 * @author Json-schema class generator
 */
class Client extends ParentClient
{
    /**
     * @var string
     */
    private $ext;

    /**
     * @return string
     */
    public function getExt(): string
    {
        return $this->ext;
    }

    /**
     * @param string $ext
     *
     * @return $this
     */
    public function setExt(string $ext)
    {
        $this->ext = $ext;

        return $this;
    }
}
