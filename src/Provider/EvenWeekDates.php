<?php

namespace Robier\WorkingDay\Provider;

use Robier\WorkingDay\Date;
use Robier\WorkingDay\Day;
use Robier\WorkingDay\Provider;
use Robier\WorkingDay\Provider\Data\MetaCollection;
use Robier\WorkingDay\Provider\Data\ShiftsCollection;

class EvenWeekDates implements Provider
{
    protected $week;

    /**
     * Week constructor.
     *
     * @param ShiftsCollection $shifts
     * @param MetaCollection   $meta
     */
    public function __construct(ShiftsCollection $shifts, MetaCollection $meta = null)
    {
        $this->week = new Week($shifts, $meta);
    }

    /**
     * {@inheritdoc}
     */
    public function get(Date $date): ?Day
    {
        if (0 != $date->day() % 2) {
            return null;
        }

        return $this->week->get($date);
    }
}
