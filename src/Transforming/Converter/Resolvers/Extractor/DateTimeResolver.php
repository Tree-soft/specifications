<?php

namespace TreeSoft\Specifications\Transforming\Converter\Resolvers\Extractor;

use TreeSoft\Specifications\Transforming\Converter\Resolvers\AbstractResolver;
use DateTime;

class DateTimeResolver extends AbstractResolver
{
    const DATETIME_SCHEMA = 'schema://common/datetime';

    /**
     * @param mixed $data
     * @param callable $next
     *
     * @return mixed
     */
    public function resolve($data, $next)
    {
        return ($this->isDateTime($data)) ?
            ($this->getValue($data)) :
            ($next($data));
    }

    /**
     * @param mixed $date
     *
     * @return bool
     */
    public function isDateTime($date): bool
    {
        return $date instanceof DateTime;
    }

    /**
     * @param DateTime $date
     *
     * @return string
     */
    public function getValue(DateTime $date): string
    {
        return $date->format(DateTime::ATOM);
    }
}
