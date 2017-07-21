<?php

namespace Robier\WorkingDay\Tests\Provider\Data;

use PHPUnit\Framework\TestCase;
use Robier\WorkingDay\Provider\Data\Collection;

class CollectionTest extends TestCase
{
    public function testSetMethod()
    {
        $collection = new Collection();

        $this->assertInstanceOf(Collection::class, $collection->set('foo', 1));
    }

    public function testGetMethod()
    {
        $collection = new Collection();

        $this->assertNull($collection->get(1));
        $this->assertNull($collection->get(2));
        $this->assertNull($collection->get(3));
    }

    public function testRemoveMethod()
    {
        $collection = new Collection();

        $this->assertInstanceOf(Collection::class, $collection->remove(1));

        $collection->set('foo', 1);

        $this->assertTrue($collection->has(1));

        $this->assertInstanceOf(Collection::class, $collection->remove(1));

        $this->assertFalse($collection->has(1));
    }
}
