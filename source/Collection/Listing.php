<?php

namespace Robier\WorkingDay\Collection;

use ArrayIterator;
use Countable;
use Iterator;
use IteratorAggregate;

class Listing implements Countable, IteratorAggregate
{
    protected $list = [];

    public function add($value): self
    {
        $this->list[] = $value;

        return $this;
    }

    public function set(array $values): self
    {
        $this->list = $values;

        return $this;
    }

    public function get(int $index, $default = null)
    {
        if($this->exists($index)){
            return $this->list[$index];
        }

        return $default;
    }

    public function exists(int $index): bool
    {
        return isset($this->list[$index]);
    }

    public function first()
    {
        return $this->get(0);
    }

    public function last()
    {
        $index = $this->count() - 1;

        if($index < 0){
            return null;
        }

        return $this->get($index);
    }

    public function sort(callable $function): self
    {
        usort($this->list, $function);

        return $this;
    }

    public function delete(int $index): bool
    {
        if($this->exists($index)){
            unset($this->list[$index]);
            return true;
        }

        return false;
    }

    public function empty(): self
    {
        $this->list = [];

        return $this;
    }

    public function all(): array
    {
        return $this->list;
    }

    public function isEmpty(): bool
    {
        return $this->count() == 0;
    }

    public function count(): int
    {
        return count($this->list);
    }

    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->list);
    }
}