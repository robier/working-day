<?php

namespace Robier\WorkingDay\Tests\Day;

use PHPUnit\Framework\TestCase;
use Robier\WorkingDay\Day\Type;
use Robier\WorkingDay\Day\Type\Iterator;
use Robier\WorkingDay\Tests\Mocks;

class IteratorTest extends TestCase
{
    use Mocks;

    public function dataProviderForIterator()
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

    /**
     * @dataProvider dataProviderForIterator
     *
     * @param int   $start
     * @param array $days
     */
    public function testIteration(int $start, array $days)
    {
        $position = 0;

        foreach (new Iterator($this->mockType($start)) as $typeId => $type) {
            $this->assertInstanceOf(Type::class, $type);
            $this->assertEquals($typeId, $type->get());
            $this->assertEquals($days[$position], $type->get());

            ++$position;
        }

        $this->assertSame(7, $position);
    }
}
