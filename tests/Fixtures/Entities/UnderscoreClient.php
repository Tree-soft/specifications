<?php

namespace TreeSoft\Tests\Specifications\Fixtures\Entities;

/**
 * @author Json-schema class generator
 */
class UnderscoreClient
{
    /**
     * @var string
     */
    private $first_name;

    /**
     * @var UnderscoreCompany
     */
    private $company;

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * @param string $first_name
     *
     * @return $this
     */
    public function setFirstName(string $first_name)
    {
        $this->first_name = $first_name;

        return $this;
    }

    /**
     * @return UnderscoreCompany
     */
    public function getCompany(): UnderscoreCompany
    {
        return $this->company;
    }

    /**
     * @param UnderscoreCompany $company
     *
     * @return $this
     */
    public function setCompany(UnderscoreCompany $company)
    {
        $this->company = $company;

        return $this;
    }
}
