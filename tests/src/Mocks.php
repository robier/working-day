<?php

namespace Robier\WorkingDay\Tests;

use DateInterval;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Robier\WorkingDay\Date;
use Robier\WorkingDay\Day;
use Robier\WorkingDay\Day\Meta;
use Robier\WorkingDay\Day\Type;
use Robier\WorkingDay\Provider;
use Robier\WorkingDay\Shift;
use Robier\WorkingDay\Shifts;
use Robier\WorkingDay\Time;

/**
 * Trait Mocks
 */
trait Mocks
{
    /**
     * @property TestCase $this
     */

    /**
     * @param string $time
     */

    /**
     * @param string $time
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Time
     */
    protected function mockTime(string $time)
    {
        $time = explode(':', $time);

        $hours = (int) $time[0];
        $minutes = isset($time[1]) ? (int) $time[1] : 0;

        $integer = ($hours * 60) + $minutes;

        $mock = $this->getMockBuilder(Time::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept(['__toString', 'toString'])
            ->getMock();

        $mock->method('hours')->willReturn($hours);
        $mock->method('minutes')->willReturn($minutes);
        $mock->method('toInteger')->willReturn($integer);

        return $mock;
    }

    /**
     * @param string $start
     * @param string $end
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Shift
     */
    protected function mockShift(string $start, string $end)
    {
        $startMock = $this->mockTime($start);
        $endMock = $this->mockTime($end);

        $mock = $this->getMockBuilder(Shift::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept(['contains', 'overlaps'])
            ->getMock();

        $mock->method('start')->willReturn($startMock);
        $mock->method('end')->willReturn($endMock);

        return $mock;
    }

    /**
     * @param array $options
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Shifts
     */
    protected function mockShifts(array $options = [])
    {
        $mock = $this->createMock(Shifts::class);

        if (isset($options['empty'])) {
            $mock->expects($this->once())
                ->method('empty')
                ->willReturn($options['empty']);
        }

        if (isset($options['isOpenedAt'])) {
            $mock->expects($this->once())
                ->method('isOpenedAt')
                ->willReturn($options['isOpenedAt']);
        }

        if (isset($options['isPauseAt'])) {
            $mock->expects($this->once())
                ->method('isPauseAt')
                ->willReturn($options['isPauseAt']);
        }

        if (isset($options['isInsideWorkingTime'])) {
            $mock->expects($this->once())
                ->method('isInsideWorkingTime')
                ->willReturn($options['isInsideWorkingTime']);
        }

        if (isset($options['isOpen24h'])) {
            $mock->expects($this->once())
                ->method('isOpen24h')
                ->willReturn($options['isOpen24h']);
        }

        return $mock;
    }

    /**
     * @param string[] $dates
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Provider
     */
    protected function mockProvider(string ...$dates)
    {
        $mock = $this->stubProvider();

        $mock->method('get')
            ->willReturnCallback(function (Date $date) use ($dates) {
                return in_array((string) $date, $dates) ? $this->stubDay() : null;
            });

        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Provider
     */
    protected function stubProvider()
    {
        return $this->createMock(Provider::class);
    }

    /**
     * @param int $type
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Type
     */
    protected function mockType(int $type)
    {
        $mock = $this->stubType();

        $mock->method('get')
            ->willReturn($type);

        $mock->method('next')
            ->willReturnCallback(function () use ($type) {
                if ($type == Type::SUNDAY) {
                    return $this->mockType(Type::MONDAY);
                }

                return $this->mockType(++$type);
            });

        $mock->method('previous')
            ->willReturnCallback(function () use ($type) {
                if ($type == Type::MONDAY) {
                    return $this->mockType(Type::SUNDAY);
                }

                return $this->mockType(--$type);
            });

        return $mock;
    }

    /**
     * @param string $date
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Date
     */
    protected function mockDate(string $date)
    {
        $dateMock = $this->stubDate();

        $dateTime = DateTimeImmutable::createFromFormat('Y-m-d', $date);

        $dateMock->method('day')->willReturn((int) $dateTime->format('j'));
        $dateMock->method('month')->willReturn((int) $dateTime->format('n'));
        $dateMock->method('year')->willReturn((int) $dateTime->format('Y'));

        $dateMock->method('toDateTime')->willReturn($dateTime);

        $dateMock->method('next')->willReturnCallback(function (int $number = 1) use ($dateTime) {
            $newDateTime = $dateTime->add(new DateInterval(sprintf('P%dD', $number)));

            return $this->mockDate($newDateTime->format('Y-m-d'));
        });

        $dateMock->method('previous')->willReturnCallback(function (int $number = 1) use ($dateTime) {
            $newDateTime = $dateTime->sub(new DateInterval(sprintf('P%dD', $number)));

            return $this->mockDate($newDateTime->format('Y-m-d'));
        });

        $dateMock->method('__toString')->willReturn($date);
        $dateMock->method('toString')->willReturn($date);
        $dateMock->method('toInteger')->willReturn((int) $dateTime->format('Ymd'));

        return $dateMock;
    }

    protected function mockDay()
    {
        $mock = $this->stubDay();
    }

    protected function stubDay()
    {
        return $this->createMock(Day::class);
    }

    /**
     * @param \int[] $types
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|Provider\Data\ShiftsCollection
     */
    protected function mockShiftsCollection(int ...$types)
    {
        $mock = $this->stubShiftsCollection();

        $mock->method('get')->willReturnCallback(function (Type $type) use ($types) {
            if (in_array($type->get(), $types)) {
                return $this->stubShifts();
            }

            return null;
        });

        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Provider\Data\MetaCollection
     */
    protected function mockMetaCollection()
    {
        $mock = $this->stubMetaCollection();

        $mock->method('get')->willReturn($this->stubMeta());

        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Provider\Data\MetaCollection
     */
    protected function stubMetaCollection()
    {
        return $this->createMock(Provider\Data\MetaCollection::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Time
     */
    protected function stubTime()
    {
        return $this->createMock(Time::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Meta
     */
    protected function stubMeta()
    {
        return $this->createMock(Meta::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Date
     */
    protected function stubDate()
    {
        return $this->createMock(Date::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Shifts
     */
    protected function stubShifts()
    {
        return $this->createMock(Shifts::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Type
     */
    protected function stubType()
    {
        return $this->createMock(Type::class);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|Provider\Data\ShiftsCollection
     */
    protected function stubShiftsCollection()
    {
        return $this->createMock(Provider\Data\ShiftsCollection::class);
    }
}
