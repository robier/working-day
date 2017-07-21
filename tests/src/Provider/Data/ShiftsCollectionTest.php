<?php

namespace Robier\WorkingDay\Tests\Provider\Data;

use PHPUnit\Framework\TestCase;
use Robier\WorkingDay\Day\Type;
use Robier\WorkingDay\Provider\Data\ShiftsCollection;
use Robier\WorkingDay\Shifts;
use Robier\WorkingDay\Tests\Mocks;

class ShiftsCollectionTest extends TestCase
{
    use Mocks;

    public function testSetMethod()
    {
        $collection = new ShiftsCollection();

        $this->assertInstanceOf(ShiftsCollection::class, $collection->set($this->stubShifts(), $this->stubType()));
    }

    public function testGetMethod()
    {
        $collection = new ShiftsCollection();

        $collection->set($this->mockShifts(), $this->mockType(Type::MONDAY), $this->mockType(Type::TUESDAY));

        $this->assertInstanceOf(Shifts::class, $collection->get($this->mockType(Type::MONDAY)));
        $this->assertInstanceOf(Shifts::class, $collection->get($this->mockType(Type::TUESDAY)));
        $this->assertNull($collection->get($this->mockType(Type::WEDNESDAY)));
        $this->assertNull($collection->get($this->mockType(Type::THURSDAY)));
        $this->assertNull($collection->get($this->mockType(Type::FRIDAY)));
        $this->assertNull($collection->get($this->mockType(Type::SATURDAY)));
        $this->assertNull($collection->get($this->mockType(Type::SUNDAY)));
    }
}
