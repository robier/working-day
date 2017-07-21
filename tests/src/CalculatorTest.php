<?php

namespace Robier\WorkingDay\Tests;

use Generator;
use PHPUnit\Framework\TestCase;
use Robier\WorkingDay\Calculator;
use Robier\WorkingDay\Date;
use Robier\WorkingDay\Day;

class CalculatorTest extends TestCase
{
    use Mocks;

    public function testGetMethod()
    {
        $this->assertInstanceOf(
            Day::class,
            (new Calculator($this->mockProvider()))->get($this->stubDate())
        );
    }

    public function testRangeMethod()
    {
        $range = (new Calculator($this->mockProvider()))->range($this->mockDate('1991-11-14'), $this->mockDate('1991-11-15'));

        $this->assertInstanceOf(
            Generator::class,
            $range
        );

        foreach ($range as $date => $day) {
            $this->assertInstanceOf(Date::class, $date);
            $this->assertInstanceOf(Day::class, $day);
        }
    }

    public function testDaysMethod()
    {
        $this->assertInstanceOf(
            Generator::class,
            (new Calculator($this->mockProvider()))->days($this->stubDate(), 5)
        );
    }

    public function testWeekMethod()
    {
        $this->assertInstanceOf(
            Generator::class,
            (new Calculator($this->mockProvider()))->week($this->stubDate(), 2));
    }
}
