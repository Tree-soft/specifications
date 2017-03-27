<?php

namespace Mildberry\Specifications\Support;

use ArrayAccess;

/**
 * Class OrderedList.
 */
class OrderedList implements ArrayAccess
{
    /**
     * @var array
     */
    private $items = [];

    /**
     * OrderedList constructor.
     *
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * @param string $value
     * @param string|null $before
     *
     * @return $this
     */
    public function add(string $value, string $before = null)
    {
        $id = isset($before) ? (array_search($before, $this->items)) : (count($this->items));

        if ($id === false) {
            $this->items[] = $value;
        } elseif ($id === 0) {
            array_unshift($this->items, $value);
        } else {
            array_splice($this->items, $id, 0, [$value]);
        }

        return $this;
    }

    /**
     * @param $value
     *
     * @return $this
     */
    public function remove($value)
    {
        $this->items = array_values(
            array_filter($this->items, function (string $item) use ($value) {
                return $value != $item;
            })
        );

        return $this;
    }

    /**
     * @return array
     */
    public function items(): array
    {
        return $this->items;
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->items);
    }

    /**
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     *
     * @return mixed
     */
    public function offsetSet($offset, $value)
    {
        return $this->items[$offset];
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }
}
