<?php

namespace Robier\WorkingDay\Tests\Provider;

use PHPUnit\Framework\TestCase;
use Robier\WorkingDay\Day;
use Robier\WorkingDay\Provider\Week;
use Robier\WorkingDay\Tests\Mocks;

class WeekTest extends TestCase
{
    use Mocks;

    public function dataProvider()
    {
        // 1991-11-14 is THURSDAY
        return
        [
            [[], '1991-11-14', false],
            [[Day\Type::MONDAY, Day\Type::TUESDAY, Day\Type::WEDNESDAY], '1991-11-14', false],
            [[Day\Type::WEDNESDAY, Day\Type::THURSDAY, Day\Type::FRIDAY], '1991-11-14', true],
            [[Day\Type::WEDNESDAY, Day\Type::THURSDAY, Day\Type::FRIDAY], '1991-11-15', true],
            [[Day\Type::THURSDAY, Day\Type::FRIDAY, Day\Type::SATURDAY], '1991-11-16', true],
            [[Day\Type::THURSDAY, Day\Type::FRIDAY, Day\Type::SATURDAY], '1991-11-17', false],
        ];
    }

    /**
     * @dataProvider dataProvider
     *
     * @param array  $dates
     * @param string $check
     * @param bool   $test
     */
    public function testGetMethod(array $dates, string $check, bool $test)
    {
        $week = new Week($this->mockShiftsCollection(...$dates), $this->mockMetaCollection());
        $result = $week->get($this->mockDate($check));

        if ($test) {
            $this->assertInstanceOf(Day::class, $result);
        } else {
            $this->assertNull($result);
        }
    }
}
