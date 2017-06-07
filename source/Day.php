<?php

namespace Robier\WorkingDay;

use DateTime;
use DateTimeZone;
use Robier\WorkingDay\Day\Type;

class Day
{
    protected $type;
    protected $shifts;

    public function __construct(Type $type, Shifts $shifts = null)
    {
        $this->type = $type;

        if(null === $shifts){
            $this->shifts = new Shifts();
        }else{
            $this->shifts = $shifts;
        }
    }

    public function type(): Type
    {
        return $this->type;
    }

    public function shifts(): Shifts
    {
        return $this->shifts;
    }

    /**
     * @param Time $start
     * @param Time $end
     *
     * @return Day
     */
    public function add(Time $start, Time $end): self
    {
        $this->shifts()->add($start, $end);

        return $this;
    }

    /**
     * Check if establishment is open on this particular day
     *
     * @return bool
     */
    public function isWorkingDay(): bool
    {
        return (bool)$this->shifts->count();
    }

    public function isOpenOn(Time $time): bool
    {
        return $this->shifts->isOpen($time);
    }

    public function isOpen(DateTimeZone $timeZone = null): bool
    {
        return $this->shifts()->isOpen(Time::now($timeZone));
    }

    public function isPause(DateTimeZone $timeZone = null): bool
    {
        return $this->shifts()->isPause(Time::now($timeZone));
    }

    public function isPauseOn(Time $time): bool
    {
        return $this->shifts()->isPause($time);
    }

    public function isMonday(): bool
    {
        return $this->type()->isMonday();
    }

    public function isTuesday(): bool
    {
        return $this->type()->isTuesday();
    }

    public function isWednesday(): bool
    {
        return $this->type()->isWednesday();
    }

    public function isThursday(): bool
    {
        return $this->type()->isThursday();
    }

    public function isFriday(): bool
    {
        return $this->type()->isFriday();
    }

    public function isSaturday(): bool
    {
        return $this->type()->isSaturday();
    }

    public function isSunday(): bool
    {
        return $this->type()->isSunday();
    }

    public function isToday(): bool
    {
        return $this->type()->isToday();
    }

    /*
     | --------------------------------
     |    factory methods
     | --------------------------------
     */

    public static function monday(Shifts $shifts = null): self
    {
        return new static(Type::monday(), $shifts);
    }

    public static function tuesday(Shifts $shifts = null): self
    {
        return new static(Type::tuesday(), $shifts);
    }

    public static function wednesday(Shifts $shifts = null): self
    {
        return new static(Type::wednesday(), $shifts);
    }

    public static function thursday(Shifts $shifts = null): self
    {
        return new static(Type::thursday(), $shifts);
    }

    public static function friday(Shifts $shifts = null): self
    {
        return new static(Type::friday(), $shifts);
    }

    public static function saturday(Shifts $shifts = null): self
    {
        return new static(Type::saturday(), $shifts);
    }

    public static function sunday(Shifts $shifts = null): self
    {
        return new static(Type::sunday(), $shifts);
    }

    public static function today(Shifts $shifts = null, DateTimeZone $timeZone = null)
    {
        return new static(Type::today($timeZone), $shifts);
    }

    public static function tomorrow(Shifts $shifts = null, DateTimeZone $timeZone = null)
    {
        return new static(Type::tomorrow($timeZone), $shifts);
    }

    public static function yesterday(Shifts $shifts = null, DateTimeZone $timeZone = null)
    {
        return new static(Type::yesterday($timeZone), $shifts);
    }

    public static function date(DateTime $dateTime, Shifts $shifts = null)
    {
        return new static(Type::date($dateTime), $shifts);
    }
}