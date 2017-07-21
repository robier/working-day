<?php

namespace Robier\WorkingDay;

use DateTimeZone;
use Robier\WorkingDay\Day\Meta;

class Day
{
    protected $date;
    protected $shifts;
    protected $meta;

    public function __construct(Date $date, Shifts $shifts, Meta $meta)
    {
        $this->date = $date;
        $this->shifts = $shifts;
        $this->meta = $meta;
    }

    /**
     * Get Date object
     *
     * @return Date
     */
    public function date(): Date
    {
        return $this->date;
    }

    /**
     * Get shifts object
     *
     * @return Shifts
     */
    public function shifts(): Shifts
    {
        return $this->shifts;
    }

    /**
     * Get meta object
     *
     * @return Meta
     */
    public function meta(): Meta
    {
        return $this->meta;
    }

    /**
     * @return bool
     */
    public function opened(): bool
    {
        return !$this->shifts()->empty();
    }

    /**
     * @return bool
     */
    public function closed(): bool
    {
        return !$this->opened();
    }

    /**
     * @param Time $time
     *
     * @return bool
     */
    public function isOpenedAt(Time $time): bool
    {
        return $this->shifts()->isOpenedAt($time);
    }

    /**
     * @param DateTimeZone|null $dateTimeZone
     *
     * @return bool
     */
    public function isOpenedNow(DateTimeZone $dateTimeZone = null): bool
    {
        return $this->isOpenedAt(Time::now($dateTimeZone));
    }

    /**
     * @param Time $time
     *
     * @return bool
     */
    public function isClosedAt(Time $time): bool
    {
        return !$this->isOpenedAt($time);
    }

    /**
     * @param DateTimeZone|null $dateTimeZone
     *
     * @return bool
     */
    public function isClosedNow(DateTimeZone $dateTimeZone = null): bool
    {
        return $this->isClosedAt(Time::now($dateTimeZone));
    }

    /**
     * @param Time $time
     *
     * @return bool
     */
    public function isPauseAt(Time $time): bool
    {
        return $this->shifts()->isPauseAt($time);
    }

    /**
     * @param DateTimeZone $dateTimeZone
     *
     * @return bool
     */
    public function isPauseNow(DateTimeZone $dateTimeZone = null): bool
    {
        return $this->isPauseAt(Time::now($dateTimeZone));
    }

    /**
     * @param Time $time
     *
     * @return bool
     */
    public function isInsideWorkingTime(Time $time): bool
    {
        return $this->shifts()->isInsideWorkingTime($time);
    }

    /**
     * @param Time $time
     *
     * @return bool
     */
    public function isOutsideWorkingTime(Time $time): bool
    {
        return !$this->isInsideWorkingTime($time);
    }

    /**
     * @return Time
     */
    public function workingTime(): Time
    {
        return $this->shifts()->workingTime();
    }

    /**
     * @return bool
     */
    public function isOpen24h(): bool
    {
        return $this->shifts()->isOpen24h();
    }
}
