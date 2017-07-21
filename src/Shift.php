<?php

namespace Robier\WorkingDay;

use InvalidArgumentException;

class Shift
{
    protected const INTEGER_24H = 1440;

    protected $start;
    protected $end;

    public function __construct(Time $start, Time $end)
    {
        $this->validate($start, $end);

        $this->start = $start;
        $this->end = $end;
    }

    protected function validate(Time $start, Time $end)
    {
        if ($start->toInteger() >= $end->toInteger()) {
            throw new InvalidArgumentException('Start time should not be greater than end time');
        }

        if ($start->toInteger() >= self::INTEGER_24H) {
            throw new InvalidArgumentException('Start time could not be greater or equal than 24 hours');
        }

        if ($end->toInteger() >= self::INTEGER_24H) {
            throw new InvalidArgumentException('End time could not be greater or equal than 24 hours');
        }
    }

    public function start(): Time
    {
        return $this->start;
    }

    public function end(): Time
    {
        return $this->end;
    }

    public function contains(Time $time): bool
    {
        return
            $this->start()->toInteger() <= $time->toInteger()
            &&
            $this->end()->toInteger() > $time->toInteger();
    }

    public function overlaps(Shift $shift): bool
    {
        return
            $this->contains($shift->start())
            ||
            $shift->contains($this->start());
    }

    public static function string(string $string): self
    {
        if (!preg_match('/^(?P<time>([0-1][0-9]|2[0-3]):[0-5][0-9])-(?P>time)$/iu', $string)) {
            throw new \InvalidArgumentException(sprintf('String should be in format hh:mm-hh:mm, %s provided', $string));
        }

        $times = [];

        foreach (explode('-', $string) as $time) {
            $times[] = Time::string($time);
        }

        return new static(...$times);
    }
}
