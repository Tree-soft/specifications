<?php

namespace Mildberry\Tests\Specifications\Fixtures\Entities;

/**
 * @author Json-schema class generator
 */
class Client
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var Company|null
     */
    private $company;

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return $this
     */
    public function setPhone(string $phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Company|null
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param Company|null $company
     *
     * @return $this
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }
}
