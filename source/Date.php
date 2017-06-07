<?php

namespace Robier\WorkingDay;

use DateTimeImmutable;
use DateTimeInterface;

class Date
{
    /**
     * @var DateTimeImmutable
     */
    protected $dateTime;

    /**
     * @var int
     */
    protected $integer;

    /**
     * @var Day\Type
     */
    protected $dayType;

    public function __construct(int $year, int $month, int $day = 1)
    {
        $this->dateTime = DateTimeImmutable::createFromFormat('Y-m-d', sprintf('%04d-%02d-%02d', $year, $month, $day));
        $this->integer = sprintf('%04d%02d%02d', $year, $month, $day);
        $this->dayType = new Day\Type((int)$this->dateTime->format('N'));
    }

    public function toDateTme(): DateTimeInterface
    {
        return $this->dateTime;
    }

    public function dayType()
    {
        return $this->dayType;
    }

}