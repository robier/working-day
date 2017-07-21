<?php

namespace Robier\WorkingDay\Tests;

use PHPUnit\Framework\TestCase;
use Robier\WorkingDay\Date;
use Robier\WorkingDay\Day;
use Robier\WorkingDay\Shifts;
use Robier\WorkingDay\Time;

class DayTest extends TestCase
{
    use Mocks;

    public function testGetters()
    {
        $stubDate = $this->stubDate();
        $stubShifts = $this->stubShifts();
        $stubMeta = $this->stubMeta();

        $day = new Day($stubDate, $stubShifts, $stubMeta);

        $this->assertInstanceOf(Date::class, $day->date());
        $this->assertInstanceOf(Shifts::class, $day->shifts());
        $this->assertInstanceOf(Day\Meta::class, $day->meta());
    }

    public function testOpenedMethod()
    {
        $stubDate = $this->stubDate();
        $stubMeta = $this->stubMeta();

        $day = new Day($stubDate, $this->mockShifts(['empty' => false]), $stubMeta);

        $this->assertTrue($day->opened());

        $day = new Day($stubDate, $this->mockShifts(['empty' => true]), $stubMeta);

        $this->assertFalse($day->opened());
    }

    public function testClosedMethod()
    {
        $stubDate = $this->stubDate();
        $stubMeta = $this->stubMeta();

        $day = new Day($stubDate, $this->mockShifts(['empty' => false]), $stubMeta);

        $this->assertFalse($day->closed());

        $day = new Day($stubDate, $this->mockShifts(['empty' => true]), $stubMeta);

        $this->assertTrue($day->closed());
    }

    public function testIsOpenedAtMethod()
    {
        $stubDate = $this->stubDate();
        $stubMeta = $this->stubMeta();
        $stubTime = $this->stubTime();

        $day = new Day($stubDate, $this->mockShifts(['isOpenedAt' => false]), $stubMeta);

        $this->assertFalse($day->isOpenedAt($stubTime));

        $day = new Day($stubDate, $this->mockShifts(['isOpenedAt' => true]), $stubMeta);

        $this->assertTrue($day->isOpenedAt($stubTime));
    }

    public function testIsClosedAtMethod()
    {
        $stubDate = $this->stubDate();
        $stubMeta = $this->stubMeta();
        $stubTime = $this->stubTime();

        $day = new Day($stubDate, $this->mockShifts(['isOpenedAt' => false]), $stubMeta);

        $this->assertTrue($day->isClosedAt($stubTime));

        $day = new Day($stubDate, $this->mockShifts(['isOpenedAt' => true]), $stubMeta);

        $this->assertFalse($day->isClosedAt($stubTime));
    }

    public function testIsOpenedNowMethod()
    {
        $stubDate = $this->stubDate();
        $stubMeta = $this->stubMeta();

        $day = new Day($stubDate, $this->mockShifts(['isOpenedAt' => true]), $stubMeta);

        $this->assertTrue($day->isOpenedNow());

        $day = new Day($stubDate, $this->mockShifts(['isOpenedAt' => false]), $stubMeta);

        $this->assertFalse($day->isOpenedNow());
    }

    public function testIsClosedNowMethod()
    {
        $stubDate = $this->stubDate();
        $stubMeta = $this->stubMeta();

        $day = new Day($stubDate, $this->mockShifts(['isOpenedAt' => true]), $stubMeta);

        $this->assertFalse($day->isClosedNow());

        $day = new Day($stubDate, $this->mockShifts(['isOpenedAt' => false]), $stubMeta);

        $this->assertTrue($day->isClosedNow());
    }

    public function testIsPauseAtMethod()
    {
        $stubDate = $this->stubDate();
        $stubMeta = $this->stubMeta();
        $stubTime = $this->stubTime();

        $day = new Day($stubDate, $this->mockShifts(['isPauseAt' => true]), $stubMeta);

        $this->assertTrue($day->isPauseAt($stubTime));

        $day = new Day($stubDate, $this->mockShifts(['isPauseAt' => false]), $stubMeta);

        $this->assertFalse($day->isPauseAt($stubTime));
    }

    public function testIsPauseNowMethod()
    {
        $stubDate = $this->stubDate();
        $stubMeta = $this->stubMeta();

        $day = new Day($stubDate, $this->mockShifts(['isPauseAt' => true]), $stubMeta);

        $this->assertTrue($day->isPauseNow());

        $day = new Day($stubDate, $this->mockShifts(['isPauseAt' => false]), $stubMeta);

        $this->assertFalse($day->isPauseNow());
    }

    public function testIsInsideWorkingTimeMethod()
    {
        $stubDate = $this->stubDate();
        $stubMeta = $this->stubMeta();
        $stubTime = $this->stubTime();

        $day = new Day($stubDate, $this->mockShifts(['isInsideWorkingTime' => true]), $stubMeta);

        $this->assertTrue($day->isInsideWorkingTime($stubTime));

        $day = new Day($stubDate, $this->mockShifts(['isInsideWorkingTime' => false]), $stubMeta);

        $this->assertFalse($day->isInsideWorkingTime($stubTime));
    }

    public function testIsOutsideWorkingTimeMethod()
    {
        $stubDate = $this->stubDate();
        $stubMeta = $this->stubMeta();
        $stubTime = $this->stubTime();

        $day = new Day($stubDate, $this->mockShifts(['isInsideWorkingTime' => true]), $stubMeta);

        $this->assertFalse($day->isOutsideWorkingTime($stubTime));

        $day = new Day($stubDate, $this->mockShifts(['isInsideWorkingTime' => false]), $stubMeta);

        $this->assertTrue($day->isOutsideWorkingTime($stubTime));
    }

    public function testWorkingTimeMethod()
    {
        $stubDate = $this->stubDate();
        $stubMeta = $this->stubMeta();
        $stubTime = $this->stubTime();

        $day = new Day($stubDate, $this->mockShifts(['workingTime' => $stubTime]), $stubMeta);

        $this->assertInstanceOf(Time::class, $day->workingTime());
    }

    public function testIsOpen24hMethod()
    {
        $stubDate = $this->stubDate();
        $stubMeta = $this->stubMeta();

        $day = new Day($stubDate, $this->mockShifts(['isOpen24h' => true]), $stubMeta);

        $this->assertTrue($day->isOpen24h());

        $day = new Day($stubDate, $this->mockShifts(['isOpen24h' => false]), $stubMeta);

        $this->assertFalse($day->isOpen24h());
    }
}
