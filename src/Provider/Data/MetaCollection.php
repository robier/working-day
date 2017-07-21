<?php

namespace Robier\WorkingDay\Provider\Data;

use Robier\WorkingDay\Day;
use Robier\WorkingDay\Day\Meta;

class MetaCollection
{
    protected $collection;

    public function __construct()
    {
        $this->collection = new Collection();

        // set default values
        $this->collection->set(
            new Meta(),
            Day\Type::MONDAY,
            Day\Type::TUESDAY,
            Day\Type::WEDNESDAY,
            Day\Type::THURSDAY,
            Day\Type::FRIDAY,
            Day\Type::SATURDAY,
            Day\Type::SUNDAY
        );
    }

    public function set(Meta $meta, Day\Type $type, Day\Type ...$types): self
    {
        $keys = [];

        array_unshift($types, $type);

        foreach ($types as $type) {
            $keys[] = $type->get();
        }

        $this->collection->set($meta, ...$keys);

        return $this;
    }

    public function get(Day\Type $type): Meta
    {
        return $this->collection->get($type->get());
    }
}
