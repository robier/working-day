<?php

namespace Robier\WorkingDay\Tests;

use DateTimeZone;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Robier\WorkingDay\Date;

class DateTest extends TestCase
{
    public function testGetters()
    {
        $date = new Date(1991, 11, 14);

        $this->assertEquals(14, $date->day());
        $this->assertEquals(11, $date->month());
        $this->assertEquals(1991, $date->year());

        $this->assertEquals(19911114, $date->toInteger());
    }

    public function testNext()
    {
        $date = new Date(1991, 11, 14);

        $this->assertEquals(15, $date->next(1)->day());
        $this->assertEquals(19, $date->next(5)->day());
    }

    public function testPrevious()
    {
        $date = new Date(1991, 11, 14);

        $this->assertEquals(13, $date->previous(1)->day());
        $this->assertEquals(9, $date->previous(5)->day());
    }

    public function dataProviderForTimeZones()
    {
        yield [null, 'DateTimeZone not provided'];

        $timezoneIdentifiers = DateTimeZone::listIdentifiers();

        foreach ($timezoneIdentifiers as $identifier) {
            yield [new DateTimeZone($identifier), sprintf('DateTimeZone for country %s', $identifier)];
        }
    }

    /**
     * @dataProvider dataProviderForTimeZones
     *
     * @param DateTimeZone|null $timeZone
     * @param string            $description
     */
    public function testTodayFactory(DateTimeZone $timeZone = null, string $description)
    {
        $date = Date::today($timeZone);

        $dateTime = new \DateTime('now', $timeZone);

        $this->assertEquals($dateTime->format('Y-m-d'), $date->toDateTime()->format('Y-m-d'), $description);
    }

    public function testTomorrowFactory()
    {
        $date = Date::tomorrow();
        $dateTime = (new \DateTime('now'))->modify('+1 day');

        $this->assertEquals($dateTime->format('Y-m-d'), $date->toDateTime()->format('Y-m-d'));
    }

    public function testYesterdayFactory()
    {
        $date = Date::yesterday();
        $dateTime = (new \DateTime('now'))->modify('-1 day');

        $this->assertEquals($dateTime->format('Y-m-d'), $date->toDateTime()->format('Y-m-d'));
    }

    public function testFactoryString()
    {
        $date = Date::string('1991-11-14');

        $this->assertEquals(1991, $date->year());
        $this->assertEquals(11, $date->month());
        $this->assertEquals(14, $date->day());
    }

    public function testFactoryStringException()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Please provide string in yyyy-mm-dd format');

        $date = Date::string('14-11-1991');
    }

    public function testToStringMethod()
    {
        $date = new Date(1991, 11, 14);

        $this->assertEquals('1991-11-14', (string) $date);
        $this->assertEquals('1991-11-14', $date->toString());
    }
}
