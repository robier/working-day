<?php

namespace Robier\WorkingDay\Tests;

use InvalidArgumentException;
use Iterator;
use PHPUnit\Framework\TestCase;
use Robier\WorkingDay\Shifts;
use Robier\WorkingDay\Time;

class ShiftsTest extends TestCase
{
    use Mocks;

    public function testShiftValidationFail()
    {
        $mocks = [$this->mockShift('13:00', '17:00'), $this->mockShift('15:00', '19:00')];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('There should be no overlapping');

        new Shifts(...$mocks);
    }

    public function testGetMethod()
    {
        $shifts = new Shifts();

        $this->assertNull($shifts->get(0));
        $this->assertNull($shifts->get(1));
        $this->assertNull($shifts->get(2));

        $mocks = [
            $this->mockShift('13:00', '17:00'),
            $this->mockShift('17:00', '19:00'),
            $this->mockShift('19:00', '21:00'),
        ];

        $shifts = new Shifts(...$mocks);

        $this->assertEquals($mocks[0], $shifts->get(0));
        $this->assertEquals($mocks[1], $shifts->get(1));
        $this->assertEquals($mocks[2], $shifts->get(2));
        $this->assertNull($shifts->get(3));
        $this->assertNull($shifts->get(4));
    }

    public function testSorting()
    {
        $mocks = [
            $this->mockShift('17:00', '19:00'),
            $this->mockShift('19:00', '21:00'),
            $this->mockShift('13:00', '17:00'),
        ];

        $shifts = new Shifts(...$mocks);

        $this->assertEquals($mocks[2], $shifts->get(0));
        $this->assertEquals($mocks[0], $shifts->get(1));
        $this->assertEquals($mocks[1], $shifts->get(2));
    }

    public function testEmptyMethod()
    {
        $shifts = new Shifts();

        $this->assertTrue($shifts->empty());

        $shifts = new Shifts($this->mockShift('10:00', '14:00'));

        $this->assertFalse($shifts->empty());
    }

    public function testCountMethod()
    {
        $shifts = new Shifts();
        $this->assertEquals(0, $shifts->count());

        $shifts = new Shifts($this->mockShift('10:00', '14:00'));
        $this->assertEquals(1, $shifts->count());

        $shifts = new Shifts($this->mockShift('10:00', '14:00'), $this->mockShift('15:00', '17:00'));
        $this->assertEquals(2, $shifts->count());
    }

    public function testOpeningMethod()
    {
        $shifts = new Shifts();

        $this->assertNull($shifts->opening());

        $shifts = new Shifts(
            $this->mockShift('10:17', '12:00'),
            $this->mockShift('13:00', '15:00'),
            $this->mockShift('17:00', '23:00')
        );

        $this->assertInstanceOf(Time::class, $shifts->opening());
        $this->assertEquals(10, $shifts->opening()->hours());
        $this->assertEquals(17, $shifts->opening()->minutes());
    }

    public function testClosingMethod()
    {
        $shifts = new Shifts();

        $this->assertNull($shifts->closing());

        $shifts = new Shifts(
            $this->mockShift('10:00', '12:00'),
            $this->mockShift('13:00', '15:00'),
            $this->mockShift('17:00', '23:33')
        );

        $this->assertInstanceOf(Time::class, $shifts->closing());
        $this->assertEquals(23, $shifts->closing()->hours());
        $this->assertEquals(33, $shifts->closing()->minutes());
    }

    public function testIfTimeIsInsideWorkingTime()
    {
        $shifts = new Shifts();

        $this->assertFalse($shifts->isInsideWorkingTime($this->mockTime('10:00')));

        $shifts = new Shifts($this->mockShift('10:00', '15:00'));

        $this->assertFalse($shifts->isInsideWorkingTime($this->mockTime('09:59')));
        $this->assertTrue($shifts->isInsideWorkingTime($this->mockTime('10:00')));
        $this->assertTrue($shifts->isInsideWorkingTime($this->mockTime('11:00')));
        $this->assertFalse($shifts->isInsideWorkingTime($this->mockTime('15:00')));
        $this->assertFalse($shifts->isInsideWorkingTime($this->mockTime('16:00')));
    }

    public function testGettingFirstShift()
    {
        $shifts = new Shifts();

        $this->assertNull($shifts->first());

        $mocks = [
            $this->mockShift('10:00', '12:00'),
            $this->mockShift('14:00', '15:00'),
            $this->mockShift('17:00', '21:00'),
        ];

        $shifts = new Shifts(...$mocks);

        $this->assertEquals($mocks[0], $shifts->first());
    }

    public function testGettingLastShift()
    {
        $shifts = new Shifts();

        $this->assertNull($shifts->last());

        $mocks = [
            $this->mockShift('10:00', '12:00'),
            $this->mockShift('14:00', '15:00'),
            $this->mockShift('17:00', '21:00'),
        ];

        $shifts = new Shifts(...$mocks);

        $this->assertEquals($mocks[2], $shifts->last());
    }

    public function testGettingShiftByTime()
    {
        $shifts = new Shifts();

        $this->assertNull($shifts->shift($this->mockTime('10:00')));

        $mocks = [
            $this->mockShift('10:00', '12:00'),
            $this->mockShift('14:00', '15:00'),
            $this->mockShift('17:00', '21:00'),
        ];

        $shifts = new Shifts(...$mocks);

        $this->assertEquals($mocks[0], $shifts->shift($this->mockTime('10:00')));
        $this->assertEquals($mocks[0], $shifts->shift($this->mockTime('11:00')));
        $this->assertNull($shifts->shift($this->mockTime('12:00')));

        $this->assertEquals($mocks[1], $shifts->shift($this->mockTime('14:00')));
        $this->assertEquals($mocks[1], $shifts->shift($this->mockTime('14:30')));
        $this->assertNull($shifts->shift($this->mockTime('15:00')));

        $this->assertEquals($mocks[1], $shifts->shift($this->mockTime('17:00')));
        $this->assertEquals($mocks[1], $shifts->shift($this->mockTime('18:30')));
        $this->assertNull($shifts->shift($this->mockTime('21:00')));
    }

    public function testIsOpenedAtMethod()
    {
        $shifts = new Shifts();

        $this->assertFalse($shifts->isOpenedAt($this->mockTime('10:00')));

        $mocks = [
            $this->mockShift('10:00', '12:00'),
            $this->mockShift('14:00', '15:00'),
            $this->mockShift('17:00', '21:00'),
        ];

        $shifts = new Shifts(...$mocks);

        $this->assertFalse($shifts->isOpenedAt($this->mockTime('09:59')));
        $this->assertTrue($shifts->isOpenedAt($this->mockTime('10:00')));
        $this->assertFalse($shifts->isOpenedAt($this->mockTime('13:00')));
        $this->assertTrue($shifts->isOpenedAt($this->mockTime('14:30')));
        $this->assertFalse($shifts->isOpenedAt($this->mockTime('16:59')));
        $this->assertTrue($shifts->isOpenedAt($this->mockTime('17:59')));
        $this->assertFalse($shifts->isOpenedAt($this->mockTime('21:00')));
    }

    public function testIsPauseAtMethod()
    {
        $shifts = new Shifts();

        $this->assertFalse($shifts->isPauseAt($this->mockTime('10:00')));

        $mocks = [
            $this->mockShift('10:00', '12:00'),
            $this->mockShift('14:00', '15:00'),
            $this->mockShift('17:00', '21:00'),
            $this->mockShift('21:15', '23:00'),
        ];

        $shifts = new Shifts(...$mocks);

        $this->assertFalse($shifts->isPauseAt($this->mockTime('09:59')));
        $this->assertFalse($shifts->isPauseAt($this->mockTime('10:00')));
        $this->assertTrue($shifts->isPauseAt($this->mockTime('13:00')));
        $this->assertFalse($shifts->isPauseAt($this->mockTime('14:30')));
        $this->assertTrue($shifts->isPauseAt($this->mockTime('16:59')));
        $this->assertFalse($shifts->isPauseAt($this->mockTime('17:59')));
        $this->assertFalse($shifts->isPauseAt($this->mockTime('21:00')));
        $this->assertTrue($shifts->isPauseAt($this->mockTime('21:01')));
        $this->assertFalse($shifts->isPauseAt($this->mockTime('23:00')));
    }

    public function testGetIteratorMethod()
    {
        $shifts = new Shifts();

        $this->assertInstanceOf(Iterator::class, $shifts->getIterator());
    }

    public function testNonStopFactory()
    {
        $shifts = Shifts::nonStop();

        $this->assertEquals(1, $shifts->count());
        $this->assertEquals('00:00', $shifts->first()->start()->toString());
        $this->assertEquals('23:59', $shifts->first()->end()->toString());
        $this->assertEquals('23:59', $shifts->last()->end()->toString());
    }

    public function dataProviderForStringBadValues()
    {
        return
            [
                ['00:00-24:00'],
                ['test'],
                ['44:99-99:99'],
            ];
    }

    /**
     * @dataProvider dataProviderForStringBadValues
     *
     * @param string $test
     */
    public function testFactoryStringBadValues(string $test)
    {
        $this->expectException(InvalidArgumentException::class);

        Shifts::string($test);
    }

    public function testFactoryString()
    {
        $shifts = Shifts::string('10:14-15:59 20:00-20:15');

        $this->assertInstanceOf(Shifts::class, $shifts);
    }

    public function testWorkingTimeMethod()
    {
        $mockShift = $this->mockShift('10:00', '15:00');

        $shifts = new Shifts($mockShift);

        $time = $shifts->workingTime();

        $this->assertInstanceOf(Time::class, $time);
        $this->assertEquals('05:00', (string) $time);

        $shifts = new Shifts();

        $this->assertEquals('00:00', (string) $shifts->workingTime());
    }

    /**
     * @return array
     */
    public function dataProviderForIsOpen24hMethod()
    {
        return
            [
                [false, [], 'No shifts provided'],
                [true, ['00:00', '23:59'], 'Full 24h'],
                [false, ['00:00', '23:58'], 'Not a 24h'],
            ];
    }

    /**
     * @dataProvider dataProviderForIsOpen24hMethod
     *
     * @param bool   $boolean
     * @param array  $shifts
     * @param string $message
     */
    public function testIsOpen24hMethod(bool $boolean, array $shifts, string $message)
    {
        if (empty($shifts)) {
            $shifts = new Shifts();
        } else {
            $shifts = new Shifts($this->mockShift(...$shifts));
        }

        $this->assertEquals($boolean, $shifts->isOpen24h());
    }
}
