<?php

namespace Appstract\Options\Test;

use Appstract\Options\Option;

class HelperTest extends BaseTest
{
    /**
     * @covers ::option
     */
    public function test_get_instance()
    {
        $this->assertInstanceOf(Option::class, option());
    }

    /**
     * @covers ::option([$key => $value])
     */
    public function test_set()
    {
        option(['foo' => 'bar']);

        $this->assertDatabaseHas('options', ['key' => 'foo', 'value' => 'bar']);
    }

    /**
     * @covers ::option($key, $default)
     */
    public function test_get()
    {
        $this->assertEquals('baz', option('foo', 'baz'));

        option(['foo' => 'bar']);

        $this->assertEquals('bar', option('foo', 'baz'));
    }

    /**
     * @covers ::option_exists($key)
     */
    public function test_exists()
    {
        $this->assertFalse(option_exists('foo'));

        option(['foo' => 'bar']);

        $this->assertTrue(option_exists('foo'));
    }

    /**
     * @covers ::option()->remove($key)
     */
    public function test_remove()
    {
        option(['foo' => 'bar']);

        $this->assertDatabaseHas('options', ['key' => 'foo', 'value' => 'bar']);

        option()->remove('foo');

        $this->assertDatabaseMissing('options', ['key' => 'foo', 'value' => 'bar']);
    }
}
