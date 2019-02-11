<?php

namespace Appstract\Options\Test;

class HelperTest extends BaseTest
{
    public function testExists()
    {
        $this->assertFalse(option_exists('foo'));

        option(['foo' => 'bar']);
        $this->assertTrue(option_exists('foo'));
    }

    public function testGet()
    {
        $this->assertEquals('baz', option('foo', 'baz'));

        option(['foo' => 'bar']);
        $this->assertEquals('bar', option('foo', 'baz'));
    }

    public function testSet()
    {
        option(['foo' => 'bar']);
        $this->assertDatabaseHas('options', ['key' => 'foo', 'value' => 'bar']);
    }
}
