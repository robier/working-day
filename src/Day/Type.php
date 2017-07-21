<?php

namespace Robier\WorkingDay\Day;

use DateTime;
use DateTimeInterface;
use DateTimeZone;
use IteratorAggregate;
use Robier\WorkingDay\Date;

/**
 * Class Type
 */
class Type implements IteratorAggregate
{
    /**
     * ISO-8601 numeric representation of days of the week (added in
     * PHP 5.1.0) ie. date('N');
     */
    public const MONDAY = 1;
    public const TUESDAY = 2;
    public const WEDNESDAY = 3;
    public const THURSDAY = 4;
    public const FRIDAY = 5;
    public const SATURDAY = 6;
    public const SUNDAY = 7;

    protected $type;

    public function __construct(int $type)
    {
        if ($type < static::MONDAY || $type > static::SUNDAY) {
            // @todo change exception
            throw new \InvalidArgumentException('Wrong number provided');
        }

        $this->type = $type;
    }

    public function get(): int
    {
        return $this->type;
    }

    public function next(): self
    {
        $value = $this->get();

        if (static::SUNDAY === $value) {
            return new static(static::MONDAY);
        }

        return new static(++$value);
    }

    public function previous(): self
    {
        $value = $this->get();

        if (static::MONDAY === $value) {
            return new static(static::SUNDAY);
        }

        return new static(--$value);
    }

    /*
     | --------------------------------
     |    checking methods
     | --------------------------------
     */

    public function is(int $type): bool
    {
        return $type == $this->type;
    }

    public function isMonday(): bool
    {
        return $this->is(static::MONDAY);
    }

    public function isTuesday(): bool
    {
        return $this->is(static::TUESDAY);
    }

    public function isWednesday(): bool
    {
        return $this->is(static::WEDNESDAY);
    }

    public function isThursday(): bool
    {
        return $this->is(static::THURSDAY);
    }

    public function isFriday(): bool
    {
        return $this->is(static::FRIDAY);
    }

    public function isSaturday(): bool
    {
        return $this->is(static::SATURDAY);
    }

    public function isSunday(): bool
    {
        return $this->is(static::SUNDAY);
    }

    public function isToday(DateTimeZone $dateTimeZone = null): bool
    {
        return $this->get() === static::today($dateTimeZone)->get();
    }

    /*
     | --------------------------------
     |    factory methods
     | --------------------------------
     */

    public static function monday(): self
    {
        return new static(static::MONDAY);
    }

    public static function tuesday(): self
    {
        return new static(static::TUESDAY);
    }

    public static function wednesday(): self
    {
        return new static(static::WEDNESDAY);
    }

    public static function thursday(): self
    {
        return new static(static::THURSDAY);
    }

    public static function friday(): self
    {
        return new static(static::FRIDAY);
    }

    public static function saturday(): self
    {
        return new static(static::SATURDAY);
    }

    public static function sunday(): self
    {
        return new static(static::SUNDAY);
    }

    public static function today(DateTimeZone $dateTimeZone = null): self
    {
        return static::dateTime(new DateTime('now', $dateTimeZone));
    }

    public static function tomorrow(DateTimeZone $dateTimeZone = null): self
    {
        return static::today($dateTimeZone)->next();
    }

    public static function yesterday(DateTimeZone $dateTimeZone = null): self
    {
        return static::today($dateTimeZone)->previous();
    }

    /**
     * Create day type from DateTime object
     *
     * @param DateTimeInterface $dateTime
     *
     * @return Type
     */
    public static function dateTime(DateTimeInterface $dateTime): self
    {
        return new static((int) $dateTime->format('N'));
    }

    /**
     * @param Date $date
     *
     * @return Type
     */
    public static function date(Date $date): self
    {
        return self::dateTime($date->toDateTime());
    }

    public function getIterator(): Type\Iterator
    {
        return new Type\Iterator($this);
    }
}
