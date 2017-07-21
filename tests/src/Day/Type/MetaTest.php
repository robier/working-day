<?php

namespace Robier\WorkingDay\Tests\Day;

use ArrayIterator;
use PHPUnit\Framework\TestCase;
use Robier\WorkingDay\Day\Meta;

class MetaTest extends TestCase
{
    public function testGetMethod()
    {
        $meta = new Meta(['foo' => 'bar']);

        $this->assertNull($meta->get('test'));
        $this->assertEquals('bar', $meta->get('foo'));
        $this->assertEquals('test', $meta->get('test', 'test'));
    }

    public function testSetMethod()
    {
        $meta = new Meta();

        $this->assertNull($meta->get('foo'));

        $this->assertInstanceOf(Meta::class, $meta->set('foo', 'bar'));

        $this->assertEquals('bar', $meta->get('foo'));
    }

    public function testRemoveMethod()
    {
        $meta = new Meta();

        $this->assertFalse($meta->has('foo'));

        $this->assertInstanceOf(Meta::class, $meta->remove('foo'));

        $meta->set('foo', 'bar');

        $this->assertTrue($meta->has('foo'));

        $this->assertInstanceOf(Meta::class, $meta->remove('foo'));

        $this->assertFalse($meta->has('foo'));
    }

    public function testArrayAccess()
    {
        $meta = new Meta();

        $this->assertFalse(isset($meta['foo']));

        $meta['foo'] = 'bar';

        $this->assertTrue(isset($meta['foo']));
        $this->assertEquals('bar', $meta['foo']);

        unset($meta['foo']);

        $this->assertFalse(isset($meta['foo']));
    }

    public function testGetIteratorMethod()
    {
        $meta = new Meta();

        $this->assertInstanceOf(ArrayIterator::class, $meta->getIterator());
    }
}
