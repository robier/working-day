<?php

namespace Robier\WorkingDay\Tests\Day;

use DateTime;
use PHPUnit\Framework\TestCase;
use Robier\WorkingDay\Day\Type;
use Robier\WorkingDay\Tests\Mocks;

class TypeTest extends TestCase
{
    use Mocks;

    public function dataProviderDates()
    {
        return
        [
            ['2017-02-13', Type::MONDAY],
            ['2017-02-14', Type::TUESDAY],
            ['2017-02-15', Type::WEDNESDAY],
            ['2017-02-16', Type::THURSDAY],
            ['2017-02-17', Type::FRIDAY],
            ['2017-02-18', Type::SATURDAY],
            ['2017-02-19', Type::SUNDAY],
        ];
    }

    public function dataProviderForIsMethods()
    {
        return
        [
            [Type::MONDAY],
            [Type::TUESDAY],
            [Type::WEDNESDAY],
            [Type::THURSDAY],
            [Type::FRIDAY],
            [Type::SATURDAY],
            [Type::SUNDAY],
        ];
    }

    public function dataProviderForNextMethod()
    {
        return
            [
                [new Type(Type::MONDAY), Type::TUESDAY],
                [new Type(Type::TUESDAY), Type::WEDNESDAY],
                [new Type(Type::WEDNESDAY), Type::THURSDAY],
                [new Type(Type::THURSDAY), Type::FRIDAY],
                [new Type(Type::FRIDAY), Type::SATURDAY],
                [new Type(Type::SATURDAY), Type::SUNDAY],
                [new Type(Type::SUNDAY), Type::MONDAY],
            ];
    }

    public function dataProviderForPreviousMethod()
    {
        return
            [
                [new Type(Type::MONDAY), Type::SUNDAY],
                [new Type(Type::TUESDAY), Type::MONDAY],
                [new Type(Type::WEDNESDAY), Type::TUESDAY],
                [new Type(Type::THURSDAY), Type::WEDNESDAY],
                [new Type(Type::FRIDAY), Type::THURSDAY],
                [new Type(Type::SATURDAY), Type::FRIDAY],
                [new Type(Type::SUNDAY), Type::SATURDAY],
            ];
    }

    public function dataProviderForFactories()
    {
        return
            [
                ['monday', Type::MONDAY],
                ['tuesday', Type::TUESDAY],
                ['wednesday', Type::WEDNESDAY],
                ['thursday', Type::THURSDAY],
                ['friday', Type::FRIDAY],
                ['saturday', Type::SATURDAY],
                ['sunday', Type::SUNDAY],
            ];
    }

    public function testBadDataProvided()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Type(99);
    }

    /**
     * @dataProvider dataProviderForIsMethods
     *
     * @param int $dayType
     */
    public function testIsMethods(int $dayType)
    {
        $type = new Type($dayType);

        $methods =
            [
                Type::MONDAY    => 'isMonday',
                Type::TUESDAY   => 'isTuesday',
                Type::WEDNESDAY => 'isWednesday',
                Type::THURSDAY  => 'isThursday',
                Type::FRIDAY    => 'isFriday',
                Type::SATURDAY  => 'isSaturday',
                Type::SUNDAY    => 'isSunday',
            ];

        for ($i = Type::MONDAY; $i <= Type::SUNDAY; ++$i) {
            if ($dayType === $i) {
                continue;
            }

            $this->assertFalse($type->is($i));
            $this->assertFalse($type->{$methods[$i]}());
        }

        $this->assertTrue($type->is($dayType));
        $this->assertTrue($type->{$methods[$dayType]}());
    }

    /**
     * @dataProvider dataProviderForNextMethod
     *
     * @param Type $type
     * @param int  $next
     */
    public function testNextMethod(Type $type, int $next)
    {
        $this->assertSame($next, $type->next()->get());
    }

    /**
     * @dataProvider dataProviderForPreviousMethod
     *
     * @param Type $type
     * @param int  $previous
     */
    public function testPreviousMethod(Type $type, int $previous)
    {
        $this->assertSame($previous, $type->previous()->get());
    }

    /**
     * @dataProvider dataProviderDates
     *
     * @param string $date
     * @param int    $type
     */
    public function testFactoryDateTime(string $date, int $type)
    {
        $this->assertEquals($type, Type::dateTime(new DateTime($date))->get());
    }

    /**
     * @dataProvider dataProviderDates
     *
     * @param string $date
     * @param int    $type
     */
    public function testFactoryDate(string $date, int $type)
    {
        $this->assertEquals($type, Type::date($this->mockDate($date))->get());
    }

    public function testFactoryToday()
    {
        $date = new DateTime();
        $dayType = (int) $date->format('N');

        $type = Type::today();

        $this->assertInstanceOf(Type::class, $type);
        $this->assertSame($dayType, $type->get());
        $this->assertTrue($type->isToday());
    }

    public function testFactoryTomorrow()
    {
        $date = new DateTime();
        $date->modify('+1 day');
        $dayType = (int) $date->format('N');

        $type = Type::tomorrow();
        $this->assertInstanceOf(Type::class, $type);
        $this->assertSame($dayType, $type->get());
    }

    public function testFactoryYesterday()
    {
        $date = new DateTime();
        $date->modify('-1 day');
        $dayType = (int) $date->format('N');

        $type = Type::yesterday();
        $this->assertInstanceOf(Type::class, $type);
        $this->assertSame($dayType, $type->get());
    }

    /**
     * @dataProvider dataProviderForFactories
     *
     * @param string $method
     * @param int    $dayType
     */
    public function testFactories(string $method, int $dayType)
    {
        /**
         * @var Type
         */
        $type = Type::{$method}();

        $this->assertInstanceOf(Type::class, $type);
        $this->assertEquals($dayType, $type->get());
    }

    public function testGetIteratorMethod()
    {
        $this->assertInstanceOf(\Iterator::class, (new Type(Type::MONDAY))->getIterator());
    }
}
