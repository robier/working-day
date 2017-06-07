<?php

use PHPUnit\Framework\TestCase;
use Robier\WorkingDay\Shift;
use Robier\WorkingDay\Shifts;
use Robier\WorkingDay\Time;

class ShiftsTest extends TestCase
{
    public function testRegisteringShifts()
    {
        $shifts = new Shifts();

        $this->assertSame(0, $shifts->count());

        $shift = new Shift(Time::string('10:00'), Time::string('12:00'));
        $shifts->register($shift);

        $this->assertSame($shift, $shifts->get(0));
        $this->assertSame(1, $shifts->count());

        $shift = new Shift(Time::string('12:30'), Time::string('14:00'));
        $shifts->register($shift);

        $this->assertSame($shift, $shifts->get(1));
        $this->assertSame(2, $shifts->count());

        $shift = new Shift(Time::string('14:30'), Time::string('16:00'));
        $shifts->register($shift);

        $this->assertSame($shift, $shifts->get(2));
        $this->assertSame(3, $shifts->count());

        $shift = new Shift(Time::string('16:30'), Time::string('19:00'));
        $shifts->register($shift);

        $this->assertSame($shift, $shifts->get(3));
        $this->assertSame(4, $shifts->count());
    }

    public function testAddingShifts()
    {
        $shifts = new Shifts();

        $this->assertSame(0, $shifts->count());

        $shifts->add(Time::string('10:00'), Time::string('12:00'));
        $this->assertSame(1, $shifts->count());

        $shifts->add(Time::string('12:30'), Time::string('14:00'));
        $this->assertSame(2, $shifts->count());

        $shifts->add(Time::string('14:30'), Time::string('16:00'));
        $this->assertSame(3, $shifts->count());

        $shifts->add(Time::string('16:30'), Time::string('19:00'));
        $this->assertSame(4, $shifts->count());
    }

    public function testOrderingOfShifts()
    {
        $shifts = new Shifts();

        $shifts->add(Time::string('16:30'), Time::string('19:00'));
        $shifts->add(Time::string('12:30'), Time::string('14:00'));
        $shifts->add(Time::string('10:00'), Time::string('12:00'));
        $shifts->add(Time::string('14:30'), Time::string('16:00'));

        $this->assertSame('10:00', (string)$shifts->get(0)->start());
        $this->assertSame('12:00', (string)$shifts->get(0)->end());

        $this->assertSame('12:30', (string)$shifts->get(1)->start());
        $this->assertSame('14:00', (string)$shifts->get(1)->end());

        $this->assertSame('14:30', (string)$shifts->get(2)->start());
        $this->assertSame('16:00', (string)$shifts->get(2)->end());

        $this->assertSame('16:30', (string)$shifts->get(3)->start());
        $this->assertSame('19:00', (string)$shifts->get(3)->end());
    }


}