<?php

namespace Mildberry\Specifications\Support;

/**
 * @author Sergei Melnikov <me@rnr.name>
 */
class DatePreparator
{
    /**
     * @param mixed $data
     *
     * @return mixed
     */
    public function prepare($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => &$value) {
                $value = $this->prepare($value);
            }

            $data = ($this->is_vector($data)) ? ($data) : ((object) $data);
        }

        return $data;
    }

    public function is_vector(array $data)
    {
        return $data === array_values($data);
    }
}
