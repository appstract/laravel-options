<?php

namespace Appstract\Options\Test;

use Appstract\Options\Option;

class HelperTest extends BaseTest
{
    /**
     * @covers ::option
     */
    public function testGetInstance()
    {
        $this->assertInstanceOf(Option::class, option());
    }

    /**
     * @covers ::option([$key => $value])
     */
    public function testSet()
    {
        option(['foo' => 'bar']);
        $this->assertDatabaseHas('options', ['key' => 'foo', 'value' => 'bar']);
    }

    /**
     * @covers ::option($key, $default)
     */
    public function testGet()
    {
        $this->assertEquals('baz', option('foo', 'baz'));

        option(['foo' => 'bar']);
        $this->assertEquals('bar', option('foo', 'baz'));
    }

    /**
     * @covers ::option_exists($key)
     */
    public function testExists()
    {
        $this->assertFalse(option_exists('foo'));

        option(['foo' => 'bar']);
        $this->assertTrue(option_exists('foo'));
    }
}
