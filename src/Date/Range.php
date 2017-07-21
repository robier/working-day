<?php

namespace Robier\WorkingDay\Date;

use Iterator;
use Robier\WorkingDay\Date;

class Range implements Iterator
{
    protected $start;
    protected $current;
    protected $end;

    protected $next = true;

    protected $key = 0;

    public function __construct(Date $start, Date $end)
    {
        if ($start->toInteger() > $end->toInteger()) {
            $this->next = false;
        }

        $this->start = $start;
        $this->current = $start;
        $this->end = $end;
    }

    public function current(): Date
    {
        return $this->current;
    }

    public function next(): void
    {
        ++$this->key;

        if ($this->next) {
            $this->current = $this->current->next();
        } else {
            $this->current = $this->current->previous();
        }
    }

    public function key(): int
    {
        return $this->key;
    }

    public function valid(): bool
    {
        if ($this->next) {
            return $this->current->toInteger() <= $this->end->toInteger();
        }

        return $this->current->toInteger() >= $this->end->toInteger();
    }

    public function rewind(): void
    {
        $this->key = 0;
        $this->current = $this->start;
    }
}
