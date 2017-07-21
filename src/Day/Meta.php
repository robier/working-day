<?php

namespace Robier\WorkingDay\Day;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use Traversable;

class Meta implements ArrayAccess, IteratorAggregate
{
    protected $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function has(string $name): bool
    {
        return isset($this->data[$name]);
    }

    public function get(string $name, $default = null)
    {
        return $this->has($name) ? $this->data[$name] : $default;
    }

    public function set(string $name, $value): self
    {
        $this->data[$name] = $value;

        return $this;
    }

    public function remove(string $name): self
    {
        if ($this->has($name)) {
            unset($this->data[$name]);
        }

        return $this;
    }

    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
    }

    public function offsetUnset($offset): void
    {
        $this->remove($offset);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->data);
    }
}
