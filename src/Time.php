<?php

namespace Robier\WorkingDay;

use DateTime;
use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;

/**
 * Class Time
 *
 * Representing time in integer form
 */
class Time
{
    protected $integer;

    protected $hours;
    protected $minutes;

    public function __construct(int $hours = 0, int $minutes = 0)
    {
        if ($hours < 0) {
            // @todo make appropriate exception
            throw new InvalidArgumentException('Hours can not be smaller than 0!');
        }

        if ($minutes < 0) {
            // @todo make appropriate exception
            throw new InvalidArgumentException('Minutes can not be smaller than 0!');
        }

        // with this we are sure that minutes will not go over 60 minutes
        $this->integer = $hours * 60 + $minutes;
        $this->calculateTime($this->integer);
    }

    protected function calculateTime($integer)
    {
        $hoursFloat = (float) ($integer / 60);
        $hours = (int) $hoursFloat;

        // default minutes value
        $minutes = 0;

        if ($hours != $hoursFloat) {
            // if float value and int value are not the same value, then
            // we have a remainder, ie. we have minutes that we need to calculate
            $minutes = (int) round((($hoursFloat - $hours) * 60), 0);
        }

        $this->hours = $hours;
        $this->minutes = $minutes;
    }

    public function hours(): int
    {
        return $this->hours;
    }

    public function minutes(): int
    {
        return $this->minutes;
    }

    public function toInteger(): int
    {
        return $this->integer;
    }

    public function toString(): string
    {
        return sprintf('%02d:%02d', $this->hours(), $this->minutes());
    }

    public function __toString()
    {
        return $this->toString();
    }

    /*
     | --------------------------------
     |    factory methods
     | --------------------------------
     */

    /**
     * Create Time object from integer
     *
     * @param int $integer
     *
     * @return Time
     */
    public static function integer(int $integer): self
    {
        return new static(0, $integer);
    }

    /**
     * Creates Time object from standard HH:mm string
     *
     * @param string $string
     *
     * @return Time
     */
    public static function string(string $string): self
    {
        $matches = null;

        if (!preg_match('/^(\d{1,2}):(\d{1,2})$/iU', $string, $matches)) {
            throw new InvalidArgumentException('String provided is not in hh:mm format');
        }

        return new static((int) $matches[1], (int) $matches[2]);
    }

    /**
     * Create Time object from string for example:
     * - 9 AM
     * - 12:35
     * - 13:22:00
     * - 9:15
     *
     * @param string $string
     *
     * @return Time
     */
    public static function time(string $string): self
    {
        $date = new DateTime($string);

        return static::dateTime($date);
    }

    /**
     * Create Time object from DateTime object
     *
     * @param DateTimeInterface $dateTime
     *
     * @return Time
     */
    public static function dateTime(DateTimeInterface $dateTime): self
    {
        return new static((int) $dateTime->format('G'), (int) $dateTime->format('i'));
    }

    /**
     * Create Time object for current time
     *
     * @param DateTimeZone|null $dateTimeZone
     *
     * @return Time
     */
    public static function now(DateTimeZone $dateTimeZone = null): self
    {
        return static::dateTime(new DateTime('now', $dateTimeZone));
    }
}
