<?php

namespace Robier\WorkingDay\Provider\Data;

/**
 * Class Collection
 *
 * Collection that holds one value for multiple keys. Like bitmask.
 */
class Collection
{
    protected $data = [];

    public function set($data, int ...$keys): self
    {
        foreach ($keys as $key) {
            $this->data[$key] = $data;
        }

        return $this;
    }

    public function get(int $key)
    {
        return $this->has($key) ? $this->data[$key] : null;
    }

    public function has(int $key): bool
    {
        return isset($this->data[$key]);
    }

    public function remove(int ...$keys): self
    {
        foreach ($keys as $key) {
            if ($this->has($key)) {
                unset($this->data[$key]);
            }
        }

        return $this;
    }
}
