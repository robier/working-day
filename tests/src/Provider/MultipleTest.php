<?php

namespace Robier\WorkingDay\Tests\Provider;

use PHPUnit\Framework\TestCase;
use Robier\WorkingDay\Day;
use Robier\WorkingDay\Provider\Multiple;
use Robier\WorkingDay\Tests\Mocks;

class MultipleTest extends TestCase
{
    use Mocks;

    public function testWithEmptyProviders()
    {
        $provider = new Multiple($this->stubProvider());

        $this->assertNull($provider->get($this->stubDate()));
    }

    public function testSingleProvider()
    {
        $provider = new Multiple($this->mockProvider('1991-11-14'));

        $this->assertInstanceOf(Day::class, $provider->get($this->mockDate('1991-11-14')));
        $this->assertNull($provider->get($this->mockDate('1991-11-15')));
    }

    public function testMultipleProviders()
    {
        $provider = new Multiple($this->mockProvider('1991-11-14'), $this->mockProvider('1991-11-15'));

        $this->assertInstanceOf(Day::class, $provider->get($this->mockDate('1991-11-14')));
        $this->assertInstanceOf(Day::class, $provider->get($this->mockDate('1991-11-15')));
        $this->assertNull($provider->get($this->mockDate('1991-11-16')));
    }
}
