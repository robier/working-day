<?php

namespace Robier\WorkingDay\Provider;

use Robier\WorkingDay\Date;
use Robier\WorkingDay\Day;
use Robier\WorkingDay\Provider;
use Robier\WorkingDay\Provider\Data\MetaCollection;
use Robier\WorkingDay\Provider\Data\ShiftsCollection;

class Week implements Provider
{
    protected $shifts;
    protected $meta;

    /**
     * @param ShiftsCollection $shifts
     * @param MetaCollection   $meta
     */
    public function __construct(ShiftsCollection $shifts, MetaCollection $meta = null)
    {
        $this->shifts = $shifts;
        $this->meta = $meta ?? new MetaCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function get(Date $date): ?Day
    {
        $weekType = Day\Type::date($date);

        $shifts = $this->shifts->get($weekType);

        return null === $shifts ? null : new Day($date, $shifts, clone $this->meta->get($weekType));
    }
}
