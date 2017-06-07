<?php

namespace Robier\WorkingDay\Group;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Robier\WorkingDay\Day;
use Traversable;

class Types implements Countable, IteratorAggregate
{
    protected $types;

    /**
     * Types constructor.
     *
     * @param Day\Type[] ...$type
     */
    public function __construct(Day\Type ...$type)
    {
        $this->types = $type;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->types);
    }
    public function count(): int
    {
        return count($this->types);
    }
}