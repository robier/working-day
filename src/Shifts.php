<?php

namespace Robier\WorkingDay;

use ArrayIterator;
use Countable;
use Iterator;
use IteratorAggregate;

class Shifts implements Countable, IteratorAggregate
{
    /**
     * Integer representation of value 23:59, ie. max possible time in one day
     */
    protected const INT_24H = 1439;

    public $shifts;

    public function __construct(Shift ...$shifts)
    {
        // check overlapping
        $this->validate(...$shifts);

        usort($shifts, function (Shift $first, Shift $last) {
            return $first->start()->toInteger() <=> $last->start()->toInteger();
        });

        $this->shifts = $shifts;
    }

    protected function validate(Shift ...$shifts)
    {
        $tempShifts = $shifts;

        $currentShift = array_shift($tempShifts);
        foreach ($tempShifts as $shift) {
            if ($currentShift->overlaps($shift)) {
                throw new \InvalidArgumentException('There should be no overlapping');
            }

            $currentShift = array_shift($tempShifts);
        }

        return false;
    }

    /**
     * Checks if establishment is open on particular time
     *
     * @param Time $time
     *
     * @return bool
     */
    public function isOpenedAt(Time $time): bool
    {
        return $this->shift($time) instanceof Shift;
    }

    /**
     * Checks if provided time is between shifts
     *
     * @param Time $time
     *
     * @return bool
     */
    public function isPauseAt(Time $time): bool
    {
        if (!$this->isInsideWorkingTime($time)) {
            // there is no shifts or time is outside current working day
            return false;
        }

        $laps = $this->count() - 1;

        for ($i = 0; $i < $laps; ++$i) {
            /**
             * @var Shift
             * @var Shift $next
             */
            $current = $this->shifts[$i];
            $next = $this->shifts[$i + 1];

            // checks shift with next shift to see if provided time is between those 2 shifts
            if (
                $current->end()->toInteger() < $time->toInteger()
                &&
                $next->start()->toInteger() > $time->toInteger()
            ) {
                return true;
            }
        }

        return false;
    }

    public function isInsideWorkingTime(Time $time)
    {
        if ($this->empty()) {
            return false;
        }

        return
            $this->first()->start()->toInteger() <= $time->toInteger()
            &&
            $this->last()->end()->toInteger() > $time->toInteger();
    }

    /**
     * Getter for shifts
     *
     * @param int $index
     *
     * @return null|Shift
     */
    public function get(int $index): ?Shift
    {
        if (!$this->has($index)) {
            return null;
        }

        return $this->shifts[$index];
    }

    /**
     * Get shift by time
     *
     * @param Time $time
     *
     * @return null|Shift
     */
    public function shift(Time $time): ?Shift
    {
        if ($this->empty()) {
            return null;
        }

        foreach ($this->shifts as $shift) {
            if ($shift->contains($time)) {
                return $shift;
            }
        }

        return null;
    }

    /**
     * Checks if shift exist
     *
     * @param int $index
     *
     * @return bool
     */
    public function has(int $index): bool
    {
        return isset($this->shifts[$index]);
    }

    public function empty(): bool
    {
        return 0 === $this->count();
    }

    /**
     * Get today's opening time
     *
     * @return null|Time
     */
    public function opening(): ?Time
    {
        if ($this->empty()) {
            return null;
        }

        return $this->first()->start();
    }

    /**
     * Get today's closing time
     *
     * @return null|Time
     */
    public function closing(): ?Time
    {
        if ($this->empty()) {
            return null;
        }

        return $this->last()->end();
    }

    /**
     * Get first shift
     *
     * @return null|Shift
     */
    public function first(): ?Shift
    {
        if ($this->empty()) {
            return null;
        }

        return $this->shifts[0];
    }

    /**
     * Get last shift
     *
     * @return null|Shift
     */
    public function last(): ?Shift
    {
        if ($this->empty()) {
            return null;
        }

        $key = $this->count() - 1;

        return $this->get($key);
    }

    /**
     * Gets number of shifts in collection
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->shifts);
    }

    /**
     * @return Time
     */
    public function workingTime(): Time
    {
        $int = 0;
        /**
         * @var Shift
         */
        foreach ($this as $shift) {
            $int += $shift->end()->toInteger() - $shift->start()->toInteger();
        }

        return Time::integer($int);
    }

    public function isOpen24h(): bool
    {
        // companies that are working whole day only have one shift from 0 to 23:59
        if ($this->count() !== 1) {
            return false;
        }

        $shift = $this->first();

        return $shift->start()->toInteger() === 0 && $shift->end()->toInteger() === static::INT_24H;
    }

    /**
     * @return Iterator
     */
    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->shifts);
    }

    /**
     * Shorthand for defining 24h working shift
     *
     * @return Shifts
     */
    public static function nonStop(): self
    {
        return new static(new Shift(new Time(), new Time(23, 59)));
    }

    /**
     * It parses string representation of shifts
     *
     * 10:15-13:20 15:00-17:00
     *
     * @param string $string
     *
     * @return Shifts
     */
    public static function string(string $string): self
    {
        // @todo fix pattern so it can not take string that have a trailing space
        if (!preg_match('/^((?P<time>([0-1][0-9]|2[0-3]):[0-5][0-9])-(?P>time)\s?)*$/iu', $string)) {
            throw new \InvalidArgumentException(sprintf('String should be in format hh:mm-hh:mm hh:mm-hh:mm..., %s provided', $string));
        }

        $shifts = [];
        foreach (explode(' ', $string) as $shift) {
            $shifts[] = Shift::string($shift);
        }

        return new static(...$shifts);
    }
}
