<?php

use PHPUnit\Framework\TestCase;
use Robier\WorkingDay\Day;
use Robier\WorkingDay\Shifts;

class DayTest extends TestCase
{

    public function testMonday()
    {
        $day = Day::monday();
        $type = Day\Type::monday();

        $this->assertSame($type->get(), $day->type()->get());

        $day = new Day($type);

        $this->assertSame($type->get(), $day->type()->get());

        $this->assertTrue($day->isMonday());
        $this->assertFalse($day->isTuesday());
        $this->assertFalse($day->isWednesday());
        $this->assertFalse($day->isThursday());
        $this->assertFalse($day->isFriday());
        $this->assertFalse($day->isSaturday());
        $this->assertFalse($day->isSunday());
    }

    public function testTuesday()
    {
        $day = Day::tuesday();
        $type = Day\Type::tuesday();

        $this->assertSame($type->get(), $day->type()->get());

        $day = new Day($type);

        $this->assertSame($type->get(), $day->type()->get());

        $this->assertFalse($day->isMonday());
        $this->assertTrue($day->isTuesday());
        $this->assertFalse($day->isWednesday());
        $this->assertFalse($day->isThursday());
        $this->assertFalse($day->isFriday());
        $this->assertFalse($day->isSaturday());
        $this->assertFalse($day->isSunday());
    }

    public function testWednesday()
    {
        $day = Day::wednesday();
        $type = Day\Type::wednesday();

        $this->assertSame($type->get(), $day->type()->get());

        $day = new Day($type);

        $this->assertSame($type->get(), $day->type()->get());

        $this->assertFalse($day->isMonday());
        $this->assertFalse($day->isTuesday());
        $this->assertTrue($day->isWednesday());
        $this->assertFalse($day->isThursday());
        $this->assertFalse($day->isFriday());
        $this->assertFalse($day->isSaturday());
        $this->assertFalse($day->isSunday());
    }

    public function testThursday()
    {
        $day = Day::thursday();
        $type = Day\Type::thursday();

        $this->assertSame($type->get(), $day->type()->get());

        $day = new Day($type);

        $this->assertSame($type->get(), $day->type()->get());

        $this->assertFalse($day->isMonday());
        $this->assertFalse($day->isTuesday());
        $this->assertFalse($day->isWednesday());
        $this->assertTrue($day->isThursday());
        $this->assertFalse($day->isFriday());
        $this->assertFalse($day->isSaturday());
        $this->assertFalse($day->isSunday());
    }

    public function testFriday()
    {
        $day = Day::friday();
        $type = Day\Type::friday();

        $this->assertSame($type->get(), $day->type()->get());

        $day = new Day($type);

        $this->assertSame($type->get(), $day->type()->get());

        $this->assertFalse($day->isMonday());
        $this->assertFalse($day->isTuesday());
        $this->assertFalse($day->isWednesday());
        $this->assertFalse($day->isThursday());
        $this->assertTrue($day->isFriday());
        $this->assertFalse($day->isSaturday());
        $this->assertFalse($day->isSunday());
    }

    public function testSaturday()
    {
        $day = Day::saturday();
        $type = Day\Type::saturday();

        $this->assertSame($type->get(), $day->type()->get());

        $day = new Day($type);

        $this->assertSame($type->get(), $day->type()->get());

        $this->assertFalse($day->isMonday());
        $this->assertFalse($day->isTuesday());
        $this->assertFalse($day->isWednesday());
        $this->assertFalse($day->isThursday());
        $this->assertFalse($day->isFriday());
        $this->assertTrue($day->isSaturday());
        $this->assertFalse($day->isSunday());
    }

    public function testSunday()
    {
        $day = Day::sunday();
        $type = Day\Type::sunday();

        $this->assertSame($type->get(), $day->type()->get());

        $day = new Day($type);

        $this->assertSame($type->get(), $day->type()->get());

        $this->assertFalse($day->isMonday());
        $this->assertFalse($day->isTuesday());
        $this->assertFalse($day->isWednesday());
        $this->assertFalse($day->isThursday());
        $this->assertFalse($day->isFriday());
        $this->assertFalse($day->isSaturday());
        $this->assertTrue($day->isSunday());
    }

    public function testShiftsGetter()
    {
        $day = new Day(Day\Type::today());

        $this->assertInstanceOf(Shifts::class, $day->shifts());


        $mock = $this->createMock(Shifts::class);
        $day = new Day(Day\Type::today(), $mock);

        $this->assertSame($mock, $day->shifts());
    }

    public function testTodayMethods()
    {
        $day = Day::today();

        $this->assertSame((int)(new DateTime())->format('N'), $day->type()->get());
        $this->assertTrue($day->isToday());
    }




}