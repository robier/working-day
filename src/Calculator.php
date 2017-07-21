<?php

namespace Robier\WorkingDay;

use Generator;
use Robier\WorkingDay\Date\Range;
use Robier\WorkingDay\Day\Meta;

class Calculator
{
    protected const DAYS_IN_WEEK = 7;

    /**
     * @var Provider
     */
    protected $provider;

    protected $closedDayMeta;

    /**
     * Calculator constructor.
     *
     * @param Provider $provider
     * @param Meta     $closedDayMeta
     */
    public function __construct(Provider $provider, Meta $closedDayMeta = null)
    {
        $this->provider = $provider;
        $this->closedDayMeta = $closedDayMeta ?? new Meta();
    }

    /**
     * @param Date $date
     *
     * @return null|Day
     */
    public function get(Date $date): Day
    {
        $day = $this->provider->get($date);

        if (null === $day) {
            $day = new Day($date, new Shifts(), clone $this->closedDayMeta);
        }

        return $day;
    }

    /**
     * @param Date $start
     * @param Date $end
     *
     * @return Generator
     */
    public function range(Date $start, Date $end): Generator
    {
        foreach (new Range($start, $end) as $date) {
            yield $date => $this->get($date);
        }
    }

    /**
     * @param Date $start
     * @param int  $total
     *
     * @return Generator
     */
    public function days(Date $start, int $total): Generator
    {
        return $this->range($start, $start->next($total));
    }

    /**
     * Get a next 7 day of provided day
     *
     * @param Date $start
     * @param int  $numberOfWeeks
     *
     * @return Generator
     */
    public function week(Date $start, int $numberOfWeeks = 1): Generator
    {
        return $this->days($start, static::DAYS_IN_WEEK * $numberOfWeeks);
    }
}
