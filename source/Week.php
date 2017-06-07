<?php

namespace Robier\WorkingDay;

use ArrayIterator;
use DateTimeZone;
use IteratorAggregate;
use Robier\WorkingDay\Day\Type;
use Traversable;

/**
 * Class Week
 *
 * Make it iterating
 */
class Week implements IteratorAggregate
{
    protected $days = [];

    public function __construct(int $startDay = Type::MONDAY)
    {
        try{
            $startDay = new Type($startDay);
        }catch (\InvalidArgumentException $e){
            throw $e;
        }

        /**
         * @var Type $type
         */
        foreach ($startDay as $type) {
            $this->days[$type->get()] = new Day($type);
        }
    }

    public function set(Day $day): self
    {
        $this->days[$day->type()->get()] = $day;
        return $this;
    }

    public function get(Type $type): Day
    {
        return $this->days[$type->get()];
    }

    public function isOpenOn(Type $type, Time $time): bool
    {
        return $this->days[$type->get()]->isOpenOn($time);
    }

    public function isOpen(DateTimeZone $timeZone = null): bool
    {
        return $this->isOpenOn(Type::today($timeZone), Time::now($timeZone));
    }

    public function isClosed(DateTimeZone $timeZone = null):bool
    {
        return !$this->isOpen($timeZone);
    }

    public function isClosedOn(Type $type, Time $time): bool
    {
        return !$this->isOpenOn($type, $time);
    }

    public function all()
    {
        return $this->days;
    }

    public function group()
    {
        return new Group($this);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->days);
    }
}

