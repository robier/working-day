<?php

use PHPUnit\Framework\TestCase;
use Robier\WorkingDay\Day\Type;

class TypeTest extends TestCase
{

    public function iteratorDataProvider()
    {
        return
            [
                [Type::MONDAY, [Type::MONDAY, Type::TUESDAY, Type::WEDNESDAY, Type::THURSDAY, Type::FRIDAY, Type::SATURDAY, Type::SUNDAY]],
                [Type::TUESDAY, [Type::TUESDAY, Type::WEDNESDAY, Type::THURSDAY, Type::FRIDAY, Type::SATURDAY, Type::SUNDAY, Type::MONDAY]],
                [Type::WEDNESDAY, [Type::WEDNESDAY, Type::THURSDAY, Type::FRIDAY, Type::SATURDAY, Type::SUNDAY, Type::MONDAY, Type::TUESDAY]],
                [Type::THURSDAY, [Type::THURSDAY, Type::FRIDAY, Type::SATURDAY, Type::SUNDAY, Type::MONDAY, Type::TUESDAY, Type::WEDNESDAY]],
                [Type::FRIDAY, [Type::FRIDAY, Type::SATURDAY, Type::SUNDAY, Type::MONDAY, Type::TUESDAY, Type::WEDNESDAY, Type::THURSDAY]],
                [Type::SATURDAY, [Type::SATURDAY, Type::SUNDAY, Type::MONDAY, Type::TUESDAY, Type::WEDNESDAY, Type::THURSDAY, Type::FRIDAY]],
                [Type::SUNDAY, [Type::SUNDAY, Type::MONDAY, Type::TUESDAY, Type::WEDNESDAY, Type::THURSDAY, Type::FRIDAY, Type::SATURDAY]],
            ];
    }

    public function datesDataProvider()
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

    public function testBadDataProvided()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Type(99);
    }

    public function testMonday()
    {
        $dayType = Type::monday();

        $this->assertInstanceOf(Type::class, $dayType);
        $this->assertSame(Type::MONDAY, $dayType->get());

        $this->assertTrue($dayType->isMonday());
        $this->assertTrue($dayType->is(Type::MONDAY));
        $this->assertFalse($dayType->isTuesday());
        $this->assertFalse($dayType->is(Type::TUESDAY));
        $this->assertFalse($dayType->isWednesday());
        $this->assertFalse($dayType->is(Type::WEDNESDAY));
        $this->assertFalse($dayType->isThursday());
        $this->assertFalse($dayType->is(Type::THURSDAY));
        $this->assertFalse($dayType->isFriday());
        $this->assertFalse($dayType->is(Type::FRIDAY));
        $this->assertFalse($dayType->isSaturday());
        $this->assertFalse($dayType->is(Type::SATURDAY));
        $this->assertFalse($dayType->isSunday());
        $this->assertFalse($dayType->is(Type::SUNDAY));

        if(Type::today() == $dayType){
            $this->assertTrue($dayType->isToday());
        }else{
            $this->assertFalse($dayType->isToday());
        }
    }

    public function testTuesday()
    {
        $dayType = Type::tuesday();

        $this->assertInstanceOf(Type::class, $dayType);
        $this->assertSame(Type::TUESDAY, $dayType->get());

        $this->assertFalse($dayType->isMonday());
        $this->assertFalse($dayType->is(Type::MONDAY));
        $this->assertTrue($dayType->isTuesday());
        $this->assertTrue($dayType->is(Type::TUESDAY));
        $this->assertFalse($dayType->isWednesday());
        $this->assertFalse($dayType->is(Type::WEDNESDAY));
        $this->assertFalse($dayType->isThursday());
        $this->assertFalse($dayType->is(Type::THURSDAY));
        $this->assertFalse($dayType->isFriday());
        $this->assertFalse($dayType->is(Type::FRIDAY));
        $this->assertFalse($dayType->isSaturday());
        $this->assertFalse($dayType->is(Type::SATURDAY));
        $this->assertFalse($dayType->isSunday());
        $this->assertFalse($dayType->is(Type::SUNDAY));

        if(Type::today() == $dayType){
            $this->assertTrue($dayType->isToday());
        }else{
            $this->assertFalse($dayType->isToday());
        }
    }

    public function testWednesday()
    {
        $dayType = Type::wednesday();

        $this->assertInstanceOf(Type::class, $dayType);
        $this->assertSame(Type::WEDNESDAY, $dayType->get());

        $this->assertFalse($dayType->isMonday());
        $this->assertFalse($dayType->is(Type::MONDAY));
        $this->assertFalse($dayType->isTuesday());
        $this->assertFalse($dayType->is(Type::TUESDAY));
        $this->assertTrue($dayType->isWednesday());
        $this->assertTrue($dayType->is(Type::WEDNESDAY));
        $this->assertFalse($dayType->isThursday());
        $this->assertFalse($dayType->is(Type::THURSDAY));
        $this->assertFalse($dayType->isFriday());
        $this->assertFalse($dayType->is(Type::FRIDAY));
        $this->assertFalse($dayType->isSaturday());
        $this->assertFalse($dayType->is(Type::SATURDAY));
        $this->assertFalse($dayType->isSunday());
        $this->assertFalse($dayType->is(Type::SUNDAY));

        if(Type::today() == $dayType){
            $this->assertTrue($dayType->isToday());
        }else{
            $this->assertFalse($dayType->isToday());
        }
    }

    public function testThursday()
    {
        $dayType = Type::thursday();

        $this->assertInstanceOf(Type::class, $dayType);
        $this->assertSame(Type::THURSDAY, $dayType->get());

        $this->assertFalse($dayType->isMonday());
        $this->assertFalse($dayType->is(Type::MONDAY));
        $this->assertFalse($dayType->isTuesday());
        $this->assertFalse($dayType->is(Type::TUESDAY));
        $this->assertFalse($dayType->isWednesday());
        $this->assertFalse($dayType->is(Type::WEDNESDAY));
        $this->assertTrue($dayType->isThursday());
        $this->assertTrue($dayType->is(Type::THURSDAY));
        $this->assertFalse($dayType->isFriday());
        $this->assertFalse($dayType->is(Type::FRIDAY));
        $this->assertFalse($dayType->isSaturday());
        $this->assertFalse($dayType->is(Type::SATURDAY));
        $this->assertFalse($dayType->isSunday());
        $this->assertFalse($dayType->is(Type::SUNDAY));

        if(Type::today() == $dayType){
            $this->assertTrue($dayType->isToday());
        }else{
            $this->assertFalse($dayType->isToday());
        }
    }

    public function testFriday()
    {
        $dayType = Type::friday();

        $this->assertInstanceOf(Type::class, $dayType);
        $this->assertSame(Type::FRIDAY, $dayType->get());

        $this->assertFalse($dayType->isMonday());
        $this->assertFalse($dayType->is(Type::MONDAY));
        $this->assertFalse($dayType->isTuesday());
        $this->assertFalse($dayType->is(Type::TUESDAY));
        $this->assertFalse($dayType->isWednesday());
        $this->assertFalse($dayType->is(Type::WEDNESDAY));
        $this->assertFalse($dayType->isThursday());
        $this->assertFalse($dayType->is(Type::THURSDAY));
        $this->assertTrue($dayType->isFriday());
        $this->assertTrue($dayType->is(Type::FRIDAY));
        $this->assertFalse($dayType->isSaturday());
        $this->assertFalse($dayType->is(Type::SATURDAY));
        $this->assertFalse($dayType->isSunday());
        $this->assertFalse($dayType->is(Type::SUNDAY));

        if(Type::today() == $dayType){
            $this->assertTrue($dayType->isToday());
        }else{
            $this->assertFalse($dayType->isToday());
        }
    }

    public function testSaturday()
    {
        $dayType = Type::saturday();

        $this->assertInstanceOf(Type::class, $dayType);
        $this->assertSame(Type::SATURDAY, $dayType->get());

        $this->assertFalse($dayType->isMonday());
        $this->assertFalse($dayType->is(Type::MONDAY));
        $this->assertFalse($dayType->isTuesday());
        $this->assertFalse($dayType->is(Type::TUESDAY));
        $this->assertFalse($dayType->isWednesday());
        $this->assertFalse($dayType->is(Type::WEDNESDAY));
        $this->assertFalse($dayType->isThursday());
        $this->assertFalse($dayType->is(Type::THURSDAY));
        $this->assertFalse($dayType->isFriday());
        $this->assertFalse($dayType->is(Type::FRIDAY));
        $this->assertTrue($dayType->isSaturday());
        $this->assertTrue($dayType->is(Type::SATURDAY));
        $this->assertFalse($dayType->isSunday());
        $this->assertFalse($dayType->is(Type::SUNDAY));

        if(Type::today() == $dayType){
            $this->assertTrue($dayType->isToday());
        }else{
            $this->assertFalse($dayType->isToday());
        }
    }

    public function testSunday()
    {
        $dayType = Type::sunday();

        $this->assertInstanceOf(Type::class, $dayType);
        $this->assertSame(Type::SUNDAY, $dayType->get());

        $this->assertFalse($dayType->isMonday());
        $this->assertFalse($dayType->is(Type::MONDAY));
        $this->assertFalse($dayType->isTuesday());
        $this->assertFalse($dayType->is(Type::TUESDAY));
        $this->assertFalse($dayType->isWednesday());
        $this->assertFalse($dayType->is(Type::WEDNESDAY));
        $this->assertFalse($dayType->isThursday());
        $this->assertFalse($dayType->is(Type::THURSDAY));
        $this->assertFalse($dayType->isFriday());
        $this->assertFalse($dayType->is(Type::FRIDAY));
        $this->assertFalse($dayType->isSaturday());
        $this->assertFalse($dayType->is(Type::SATURDAY));
        $this->assertTrue($dayType->isSunday());
        $this->assertTrue($dayType->is(Type::SUNDAY));

        if(Type::today() == $dayType){
            $this->assertTrue($dayType->isToday());
        }else{
            $this->assertFalse($dayType->isToday());
        }
    }

    public function testNext()
    {
        $dayType = Type::monday();
        $this->assertSame($dayType->next()->get(), Type::TUESDAY);

        $dayType = Type::tuesday();
        $this->assertSame($dayType->next()->get(), Type::WEDNESDAY);

        $dayType = Type::wednesday();
        $this->assertSame($dayType->next()->get(), Type::THURSDAY);

        $dayType = Type::thursday();
        $this->assertSame($dayType->next()->get(), Type::FRIDAY);

        $dayType = Type::friday();
        $this->assertSame($dayType->next()->get(), Type::SATURDAY);

        $dayType = Type::saturday();
        $this->assertSame($dayType->next()->get(), Type::SUNDAY);

        $dayType = Type::sunday();
        $this->assertSame($dayType->next()->get(), Type::MONDAY);
    }

    public function testPrevious()
    {
        $dayType = Type::monday();
        $this->assertSame($dayType->previous()->get(), Type::SUNDAY);

        $dayType = Type::sunday();
        $this->assertSame($dayType->previous()->get(), Type::SATURDAY);

        $dayType = Type::saturday();
        $this->assertSame($dayType->previous()->get(), Type::FRIDAY);

        $dayType = Type::friday();
        $this->assertSame($dayType->previous()->get(), Type::THURSDAY);

        $dayType = Type::thursday();
        $this->assertSame($dayType->previous()->get(), Type::WEDNESDAY);

        $dayType = Type::wednesday();
        $this->assertSame($dayType->previous()->get(), Type::TUESDAY);

        $dayType = Type::tuesday();
        $this->assertSame($dayType->previous()->get(), Type::MONDAY);
    }

    /**
     * @dataProvider datesDataProvider
     *
     * @param string $date
     * @param int $dayType
     */
    public function testDate(string $date, int $dayType)
    {
        $type = Type::date(new DateTime($date));
        $this->assertSame($dayType, $type->get());
    }

    public function testToday()
    {
        $date = new DateTime();
        $dayType = (int)$date->format('N');

        $type = Type::today();

        $this->assertSame($dayType, $type->get());
        $this->assertTrue($type->isToday());
    }

    public function testTomorrow()
    {
        $date = new DateTime();
        $date->modify('+1 day');
        $dayType = (int)$date->format('N');

        $type = Type::tomorrow();

        $this->assertSame($dayType, $type->get());
    }

    public function testYesterday()
    {
        $date = new DateTime();
        $date->modify('-1 day');
        $dayType = (int)$date->format('N');

        $type = Type::yesterday();

        $this->assertSame($dayType, $type->get());
    }

    /**
     * @dataProvider iteratorDataProvider
     *
     * @param int $start
     * @param array $days
     */
    public function testIteration(int $start, array $days)
    {
        $position = 0;
        foreach(new Type($start) as $typeId => $type){
            $this->assertSame($days[$position], $typeId);
            $this->assertTrue($type->is($days[$position]));

            ++$position;
        }

        $this->assertSame(7, $position);
    }

}
