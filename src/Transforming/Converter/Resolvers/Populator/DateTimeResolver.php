<?php

namespace TreeSoft\Specifications\Transforming\Converter\Resolvers\Populator;

use TreeSoft\Specifications\Transforming\Converter\Resolvers\AbstractResolver;
use DateTime;

/**
 * Class DateTimeResolver.
 */
class DateTimeResolver extends AbstractResolver
{
    const DATETIME_SCHEMA = 'schema://common/datetime';

    /**
     * @param mixed $data
     * @param callable $next
     *
     * @return mixed|DateTime
     */
    public function resolve($data, $next)
    {
        return ($this->isDateTime($data)) ?
            ($this->createDateTime($data)) :
            ($next($data));
    }

    /**
     * @param $data
     *
     * @return bool
     */
    public function isDateTime($data): bool
    {
        return !empty($data) && isset($this->schema->id) && ($this->schema->id == self::DATETIME_SCHEMA);
    }

    /**
     * @param $data
     *
     * @return DateTime
     */
    public function createDateTime($data): DateTime
    {
        $date = new DateTime($data);

        return $date;
    }
}
