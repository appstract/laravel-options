<?php

namespace Appstract\Options\Test;

use Appstract\Options\Option;

class HelperTest extends BaseTest
{
    /** @test */
    public function it_can_get_instance()
    {
        $this->assertInstanceOf(Option::class, option());
    }

    /** @test */
    public function it_can_set()
    {
        option(['foo' => 'bar']);

        $this->assertDatabaseHas('options', ['key' => 'foo', 'value' => '"bar"']);
    }

    /** @test */
    public function it_can_get_default()
    {
        $this->assertEquals('baz', option('foo', 'baz'));
    }

    /** @test */
    public function it_can_get()
    {
        option(['foo' => 'bar']);

        $this->assertEquals('bar', option('foo', 'baz'));
    }

    /** @test */
    public function it_can_check_if_exists()
    {
        $this->assertFalse(option_exists('foo'));

        option(['foo' => 'bar']);

        $this->assertTrue(option_exists('foo'));
    }

    /** @test */
    public function it_can_remove()
    {
        option(['foo' => 'bar']);

        $this->assertDatabaseHas('options', ['key' => 'foo', 'value' => '"bar"']);

        option()->remove('foo');

        $this->assertDatabaseMissing('options', ['key' => 'foo', 'value' => 'bar']);
    }

    /** @test */
    public function it_can_set_array_value()
    {
        option(['foo' => ['bar', 'baz']]);

        $this->assertEquals(['bar', 'baz'], option('foo'));
    }

    /** @test */
    public function it_can_set_number_value()
    {
        option(['foo' => 123.45]);

        $this->assertEquals(123.45, option('foo'));
    }

    /** @test */
    public function it_can_set_boolean_value()
    {
        option(['foo' => false]);

        $this->assertEquals(false, option('foo'));
    }
}
