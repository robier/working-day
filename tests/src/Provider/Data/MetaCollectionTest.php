<?php

namespace Robier\WorkingDay\Tests\Provider\Data;

use PHPUnit\Framework\TestCase;
use Robier\WorkingDay\Day\Meta;
use Robier\WorkingDay\Day\Type;
use Robier\WorkingDay\Provider\Data\MetaCollection;
use Robier\WorkingDay\Tests\Mocks;

class MetaCollectionTest extends TestCase
{
    use Mocks;

    public function testSetMethod()
    {
        $collection = new MetaCollection();

        $this->assertInstanceOf(MetaCollection::class, $collection->set($this->stubMeta(), $this->stubType()));
    }

    public function testGetMethod()
    {
        $collection = new MetaCollection();

        $this->assertInstanceOf(Meta::class, $collection->get($this->mockType(Type::MONDAY)));
        $this->assertInstanceOf(Meta::class, $collection->get($this->mockType(Type::TUESDAY)));
        $this->assertInstanceOf(Meta::class, $collection->get($this->mockType(Type::WEDNESDAY)));
        $this->assertInstanceOf(Meta::class, $collection->get($this->mockType(Type::THURSDAY)));
        $this->assertInstanceOf(Meta::class, $collection->get($this->mockType(Type::FRIDAY)));
        $this->assertInstanceOf(Meta::class, $collection->get($this->mockType(Type::SATURDAY)));
        $this->assertInstanceOf(Meta::class, $collection->get($this->mockType(Type::SUNDAY)));
    }
}
