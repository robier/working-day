<?php

use PHPUnit\Framework\TestCase;
use Robier\WorkingDay\Shift;
use Robier\WorkingDay\Time;

class ShiftTest extends TestCase
{
    public function dataProviderContains()
    {
        return
            [
                ['01:00', ['02:00', '05:00'], false, 'Before'],
                ['02:00', ['02:00', '05:00'], true,  'Touching start'],
                ['02:00', ['01:00', '05:00'], true,  'Inside'],
                ['05:00', ['02:00', '05:00'], false, 'Touching end'],
                ['06:00', ['02:00', '05:00'], false, 'After'],
            ];
    }

    public function dataProviderOverlapping()
    {
        return
            [
                [['01:00', '02:00'], ['05:00', '06:00'], false, 'Before'],
                [['01:00', '02:00'], ['02:00', '06:00'], false, 'Start touching'],
                [['01:00', '04:00'], ['03:00', '06:00'], true,  'Start inside'],
                [['04:00', '06:00'], ['04:00', '05:00'], true,  'Inside start touching'],
                [['04:00', '06:00'], ['04:00', '07:00'], true,  'Enclosing start touching'],
                [['05:00', '06:00'], ['04:00', '07:00'], true,  'Enclosing'],
                [['05:00', '06:00'], ['04:00', '06:00'], true,  'Enclosing end touching'],
                [['05:00', '06:00'], ['05:00', '06:00'], true,  'Exact match'],
                [['04:00', '07:00'], ['05:00', '06:00'], true,  'Inside'],
                [['04:00', '07:00'], ['05:00', '07:00'], true,  'Inside end touching'],
                [['05:00', '07:00'], ['04:00', '06:00'], true,  'End inside'],
                [['05:00', '06:00'], ['06:00', '07:00'], false, 'End touching'],
                [['05:00', '06:00'], ['01:00', '02:00'], false, 'After'],
            ];
    }

    public function dataProviderWorkingTime()
    {
        return
            [
                [['01:00', '05:00'], 240],
                [['01:05', '05:10'], 245],
                [['00:00', '23:59'], 1439],
                [['00:00', '00:01'], 1],
            ];
    }

    public function dataProviderExceptionalData()
    {
        return
            [
                ['03:05', '02:05', 'End time greater then start time'],
                ['03:05', '03:05', 'Start time is equal to end time'],
                ['24:00', '30:00', 'Start time equal to 24:00'],
                ['24:20', '30:00', 'Start time greater than 24:00'],
                ['18:20', '24:00', 'End time equal to 24:00'],
                ['18:20', '24:25', 'End time greater than 24:00'],
            ];
    }

    /**
     * @dataProvider dataProviderExceptionalData
     *
     * @param string $start
     * @param string $end
     * @param string $scenario
     */
    public function testExceptions(string $start, string $end, string $scenario)
    {
        $start = Time::string($start);
        $end = Time::string($end);

        $this->expectException(InvalidArgumentException::class);

        new Shift($start, $end);
    }
//
//    public function testStartTimeGreaterThanEndTime()
//    {
//
//        $start = new Time(3, 5);
//        $end = new Time(2, 5);
//
//        $this->expectException(InvalidArgumentException::class);
//
//        new Shift($start, $end);
//    }
//
//    public function testStartTimeEqualToEndTIme()
//    {
//        $start = new Time(3, 5);
//        $end = new Time(3, 5);
//
//        $this->expectException(InvalidArgumentException::class);
//
//        new Shift($start, $end);
//    }
//
//    public function testStartTimeEqualTo24Hours()
//    {
//        $start = new Time(24, 0);
//        $end = new Time(24, 5);
//
//        $this->expectException(InvalidArgumentException::class);
//
//        new Shift($start, $end);
//    }
//
//    public function testStartTimeGreaterTo24Hours()
//    {
//        $start = new Time(24, 1);
//        $end = new Time(24, 5);
//
//        $this->expectException(InvalidArgumentException::class);
//
//        new Shift($start, $end);
//    }
//
//    public function testEndTimeEqualTo24Hours()
//    {
//        $start = new Time(10, 1);
//        $end = new Time(24, 0);
//
//        $this->expectException(InvalidArgumentException::class);
//
//        new Shift($start, $end);
//    }
//
//    public function testEndTimeGreaterThan24Hours()
//    {
//        $start = new Time(10, 1);
//        $end = new Time(24, 5);
//
//        $this->expectException(InvalidArgumentException::class);
//
//        new Shift($start, $end);
//    }

    public function testStartEndGetters()
    {
        $start = new Time(9);
        $end = new Time(17);

        $shift = new Shift($start, $end);
        $this->assertSame($start, $shift->start());
        $this->assertSame($end, $shift->end());
    }

    /**
     * @dataProvider dataProviderContains
     *
     * @param string $test
     * @param array $shift
     * @param bool $bool
     * @param string $message
     */
    public function testContains(string $test, array $shift, bool $bool, string $message)
    {
        $start = Time::string($shift[0]);
        $end = Time::string($shift[1]);

        $shift = new Shift($start, $end);

        $test = Time::string($test);

        if($bool){
            $this->assertTrue($shift->contains($test), $message);
        }else{
            $this->assertFalse($shift->contains($test), $message);
        }
    }

    /**
     * @dataProvider dataProviderOverlapping
     *
     * @param array $firstShift
     * @param array $secondShift
     * @param bool $bool
     * @param string $message
     */
    public function testOverlapping(array $firstShift, array $secondShift, bool $bool, string $message)
    {
        $firstShift = new Shift(Time::string($firstShift[0]), Time::string($firstShift[1]));
        $secondShift = new Shift(Time::string($secondShift[0]), Time::string($secondShift[1]));

        if($bool){
            // it should work in any direction :)
            $this->assertTrue($firstShift->overlaps($secondShift), $message);
            $this->assertTrue($secondShift->overlaps($firstShift), $message);
        }else{
            $this->assertFalse($firstShift->overlaps($secondShift), $message);
            $this->assertFalse($secondShift->overlaps($firstShift), $message);
        }
    }

    /**
     * @dataProvider dataProviderWorkingTime
     *
     * @param array $shift
     * @param int $timeInteger
     */
    public function testWorkingTime(array $shift, int $timeInteger)
    {
        $shift = new Shift(Time::string($shift[0]), Time::string($shift[1]));

        $this->assertSame($timeInteger, $shift->workingTime()->toInteger());
    }
}
