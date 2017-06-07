<?php

namespace Robier\WorkingDay;

use Robier\WorkingDay\Collection\Listing;

class Shifts implements \Countable
{
    /**
     * @var Listing $shifts
     */
    protected $shifts;

    public function __construct()
    {
        $this->shifts = new Listing();
    }

    /**
     * Adds new Shift to collection
     *
     * @param Time $start
     * @param Time $end
     *
     * @return Shifts
     */
    public function add(Time $start, Time $end)
    {
        return $this->register(new Shift($start, $end));
    }

    /**
     * Adds new Shift to the collection
     *
     * @param Shift $shift
     *
     * @return Shifts
     */
    public function register(Shift $shift): self
    {
        foreach($this->shifts as $registeredShifts){
            if($registeredShifts->overlaps($shift)){
                throw new \InvalidArgumentException('There should be no overlapping');
            }
        }

        $this->shifts->add($shift);

        // order shifts
        $this->shifts->sort(function(Shift $shiftOne, Shift $shiftTwo){
            return $shiftOne->start() <=> $shiftTwo->start();
        });

        return $this;
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
        return $this->shifts->get($index);
    }

    public function remove(int $index): bool
    {
        return $this->shifts->delete($index);
    }

    /**
     * Checks if shift exist
     *
     * @param int $index
     *
     * @return bool
     */
    public function exists(int $index): bool
    {
        return $this->shifts->exists($index);
    }

    /**
     * Gets number of shifts in collection
     *
     * @return int
     */
    public function count(): int
    {
        return $this->shifts->count();
    }

    /**
     * Checks if it's only one shift in collection
     *
     * @return bool
     */
    public function isSingle(): bool
    {
        return $this->count() == 1;
    }

    /**
     * Checks if there are multiple shifts in collection
     *
     * @return bool
     */
    public function isMulti(): bool
    {
        return $this->count() > 1;
    }

    /**
     * Checks if establishment is open on particular time
     *
     * @param Time $time
     *
     * @return bool
     */
    public function isOpen(Time $time): bool
    {
        foreach($this->shifts as $shift){
            if($shift->isOpen($time)){
                return true;
            }
        }

        return false;
    }

    /**
     * Get first shift
     *
     * @return null|Shift
     */
    public function first(): ?Shift
    {
        return $this->shifts->first();
    }

    /**
     * Get last shift
     *
     * @return null|Shift
     */
    public function last(): ?Shift
    {
        return $this->shifts->last();
    }

    /**
     * Checks if provided time is between shifts
     *
     * @param Time $time
     *
     * @return bool
     */
    public function isPause(Time $time): bool
    {
        if($this->isSingle()){
            // there is no shifts
            return false;
        }

        if($this->isOutsideWorkingTime($time)){
            // time is outside current working day
            return false;
        }

        $laps = $this->count() - 2;
        $shifts = $this->shifts->all();

        for($i = 0; $i < $laps; $i++){
            /**
             * @var Shift $current
             * @var Shift $next
             */
            $current = $shifts[$i];
            $next = $shifts[$i + 1];

            // checks shift with next shift to see if provided time is between
            // those 2 shifts
            if($current->end() <= $time && $time < $next->start()){
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if provided time is outside working day
     *
     * @param Time $time
     *
     * @return bool
     */
    public function isOutsideWorkingTime(Time $time): bool
    {
        return $this->first()->start() > $time || $this->last()->end() <= $time;
    }

    /**
     * Get today's opening time
     *
     * @return null|Time
     */
    public function opening(): ?Time
    {
        if($this->shifts->isEmpty()){
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
        if($this->shifts->isEmpty()){
            return null;
        }

        return $this->last()->end();
    }

    /**
     * @return Time
     */
    public function workingTime(): Time
    {
        $time = 0;
        foreach($this->shifts as $shift){
            $time += $shift->workingTime()->toInteger();
        }

        return Time::integer($time);
    }
}