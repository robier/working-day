<?php

namespace Robier\WorkingDay\Day\Type;

use Robier\WorkingDay\Day\Type;

/**
 * Class TypeIterator
 */
class Iterator implements \Iterator
{
    protected $counter = 0;

    protected $current;
    protected $start;

    /**
     * TypeIterator constructor.
     *
     * @param Type $type
     */
    public function __construct(Type $type)
    {
        $this->start = $type;
        $this->current = $type;
    }

    /**
     * @return Type
     */
    public function current(): Type
    {
        return $this->current;
    }

    /**
     * @return void
     */
    public function next(): void
    {
        ++$this->counter;
        $this->current = $this->current->next();
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->current->get();
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return $this->counter < 7;
    }

    /**
     * @return void
     */
    public function rewind(): void
    {
        $this->counter = 0;
        $this->current = $this->start;
    }
}
