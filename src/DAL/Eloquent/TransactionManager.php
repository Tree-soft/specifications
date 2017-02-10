<?php

namespace Mildberry\Specifications\DAL\Eloquent;

use Mildberry\Specifications\Core\Interfaces\TransactionInterface;
use Rnr\Resolvers\Interfaces\DatabaseAwareInterface;
use Rnr\Resolvers\Traits\DatabaseAwareTrait;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class TransactionManager implements TransactionInterface, DatabaseAwareInterface
{
    use DatabaseAwareTrait;

    public function start()
    {
        $this->db->beginTransaction();
    }

    public function commit()
    {
        $this->db->commit();
    }

    public function rollback()
    {
        $this->db->rollBack();
    }
}
