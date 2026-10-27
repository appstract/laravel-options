<?php

namespace Appstract\Options\Test;

use Appstract\Options\Option;
use Illuminate\Support\Facades\Blade;

class HelperTest extends Base
{
    /** @test */
    public function test_it_can_get_instance()
    {
        $this->assertInstanceOf(Option::class, option());
    }

    /** @test */
    public function test_it_can_render_the_option_blade_directive_with_and_without_a_default()
    {
        option(['foo' => 'bar']);

        $this->assertSame('bar', Blade::render("@option('foo')"));
        $this->assertSame('baz', Blade::render("@option('missing', 'baz')"));
    }

    /** @test */
    public function test_it_can_set()
    {
        option(['foo' => 'bar']);

        $this->assertDatabaseHas('options', ['key' => 'foo', 'value' => '"bar"']);
    }

    /** @test */
    public function test_it_can_get_default()
    {
        $this->assertEquals('baz', option('foo', 'baz'));
    }

    /** @test */
    public function test_it_can_get()
    {
        option(['foo' => 'bar']);

        $this->assertEquals('bar', option('foo', 'baz'));
    }

    /** @test */
    public function test_it_can_check_if_exists()
    {
        $this->assertFalse(option_exists('foo'));

        option(['foo' => 'bar']);

        $this->assertTrue(option_exists('foo'));
    }

    /** @test */
    public function test_it_can_remove()
    {
        option(['foo' => 'bar']);

        $this->assertDatabaseHas('options', ['key' => 'foo', 'value' => '"bar"']);

        option()->remove('foo');

        $this->assertDatabaseMissing('options', ['key' => 'foo', 'value' => '"bar"']);
    }

    /** @test */
    public function test_it_can_store_arrays()
    {
        option(['foo' => ['bar', 'baz']]);

        $this->assertDatabaseHas('options', ['key' => 'foo', 'value' => '["bar","baz"]']);
        $this->assertEquals(option('foo'), ['bar', 'baz']);
    }
}
