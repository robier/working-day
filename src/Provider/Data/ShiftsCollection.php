<?php

namespace Robier\WorkingDay\Provider\Data;

use Robier\WorkingDay\Day;
use Robier\WorkingDay\Shifts;

class ShiftsCollection
{
    protected $collection;

    public function __construct()
    {
        $this->collection = new Collection();
    }

    public function set(Shifts $shifts, Day\Type $type, Day\Type ...$types): self
    {
        $keys = [];

        array_unshift($types, $type);

        foreach ($types as $type) {
            $keys[] = $type->get();
        }

        $this->collection->set($shifts, ...$keys);

        return $this;
    }

    public function get(Day\Type $type): ?Shifts
    {
        return $this->collection->get($type->get());
    }
}
