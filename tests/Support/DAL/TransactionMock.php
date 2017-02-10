<?php

namespace Mildberry\Tests\Specifications\Support\DAL;

use Mildberry\Specifications\Core\Interfaces\TransactionInterface;
use PHPUnit\Framework\TestCase;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class TransactionMock implements TransactionInterface
{
    /**
     * @var bool
     */
    public $wasStarted = false;

    /**
     * @var bool
     */
    public $wasCommitted = false;

    /**
     * @var bool
     */
    public $started = false;

    /**
     * @var bool
     */
    public $wasRollback = false;

    public function start()
    {
        $this->started = true;
        $this->wasStarted = true;
    }

    public function commit()
    {
        TestCase::assertTrue($this->started);

        $this->started = false;
        $this->wasCommitted = true;
    }

    public function rollback()
    {
        TestCase::assertTrue($this->started);

        $this->started = false;
        $this->wasRollback = true;
    }
}
