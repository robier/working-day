<?php

namespace Robier\WorkingDay\Group;

use Robier\WorkingDay\Day;
use Robier\WorkingDay\Shifts;

class Item
{
    protected $shifts;
    protected $types;

    /**
     * Item constructor.
     *
     * @param Shifts $shifts
     * @param Day\Type[] ...$type
     */
    public function __construct(Shifts $shifts, Day\Type ...$type)
    {
        $this->shifts = $shifts;

        $this->types = new Types(...$type);
    }

    public function shifts(): Shifts
    {
        return $this->shifts;
    }

    public function types(): Types
    {
        return $this->types;
    }
}