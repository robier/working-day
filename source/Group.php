<?php

namespace Robier\WorkingDay;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

class Group implements Countable, IteratorAggregate
{

    protected $groups = [];

    protected $week;

    public function __construct(Week $week)
    {
        $this->week = $week;

        $data = [];

        /**
         * @var Day $day
         */
        foreach($week as $day){

            foreach($data as $key => $item){
                if($day->shifts() == $item['shifts']){
                    array_push($data[$key]['types'], $day->type());
                    continue 2;
                }
            }
            $data[] = ['shifts' => $day->shifts(), 'types' => [$day->type()]];
        }

        foreach($data as $item){
            $this->groups[] = new Group\Item($item['shifts'], ...$item['types']);
        }
    }

    public function week(): Week
    {
        return $this->week;
    }

    public function get(): array
    {
        return $this->groups;
    }

    public function count(): int
    {
        return count($this->groups);
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->groups);
    }
}
