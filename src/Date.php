<?php

namespace Robier\WorkingDay;

use DateInterval;
use DateTime;
use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;

class Date
{
    /**
     * @var int
     */
    protected $integer;

    protected $year;
    protected $month;
    protected $day;

    public function __construct(int $year, int $month = 1, int $day = 1)
    {
        $this->integer = (int) sprintf('%04d%02d%02d', $year, $month, $day);

        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
    }

    public function next(int $number = 1): self
    {
        $dateTime = $this->toDateTime()->add(new DateInterval(sprintf('P%dD', $number)));

        return static::dateTime($dateTime);
    }

    public function previous(int $number = 1): self
    {
        $dateTime = $this->toDateTime()->sub(new DateInterval(sprintf('P%dD', $number)));

        return static::dateTime($dateTime);
    }

    public function toDateTime(): DateTime
    {
        return DateTime::createFromFormat('Ymd', $this->integer);
    }

    public function toInteger(): int
    {
        return $this->integer;
    }

    public function year(): int
    {
        return $this->year;
    }

    public function month(): int
    {
        return $this->month;
    }

    public function day(): int
    {
        return $this->day;
    }

    public function toString(): string
    {
        return sprintf('%04d-%02d-%02d', $this->year(), $this->month(), $this->day());
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public static function dateTime(DateTimeInterface $dateTime): self
    {
        $year = (int) $dateTime->format('Y');
        $month = (int) $dateTime->format('n');
        $day = (int) $dateTime->format('j');

        return new static($year, $month, $day);
    }

    public static function string(string $date, DateTimeZone $dateTimeZone = null): self
    {
        $dateTime = DateTime::createFromFormat('Y-m-d', $date, $dateTimeZone);

        if (is_bool($dateTime)) {
            throw new InvalidArgumentException('Please provide string in yyyy-mm-dd format');
        }

        return static::dateTime($dateTime);
    }

    public static function today(DateTimeZone $dateTimeZone = null): self
    {
        return static::dateTime(new DateTime('now', $dateTimeZone));
    }

    public static function tomorrow(DateTimeZone $dateTimeZone = null): self
    {
        return self::today($dateTimeZone)->next();
    }

    public static function yesterday(DateTimeZone $dateTimeZone = null): self
    {
        return self::today($dateTimeZone)->previous();
    }
}
