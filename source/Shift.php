<?php

namespace Robier\WorkingDay;

use InvalidArgumentException;

class Shift
{
    protected const INTEGER_24H = 1440;

    protected $start;
    protected $end;

    protected $workingTime;

    public function __construct(Time $start, Time $end)
    {
        if($start >= $end){
            throw new InvalidArgumentException('Start time should not be greater than end time');
        }

        if($start->toInteger() >= self::INTEGER_24H){
            throw new InvalidArgumentException('Start time could not be greater or equal than 24 hours');
        }

        if($end->toInteger() >= self::INTEGER_24H){
            throw new InvalidArgumentException('End time could not be greater or equal than 24 hours');
        }

        $this->start = $start;
        $this->end = $end;
    }

    public function start(): Time
    {
        return $this->start;
    }

    public function end(): Time
    {
        return $this->end;
    }

    public function contains(Time $time) : bool
    {
        return
            $this->start() <= $time
            &&
            $this->end() > $time;
    }

    public function overlaps(Shift $shift): bool
    {
        return
            $this->contains($shift->start())
            ||
            $shift->contains($this->start());
    }

    public function workingTime(): Time
    {
        if (empty($this->workingTime)) {
            $this->workingTime = Time::integer($this->end->toInteger() - $this->start()->toInteger());
        }
        return $this->workingTime;
    }
}