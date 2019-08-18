<?php

namespace Appstract\Options\Test;

use Appstract\Options\ObserverConfig;
use Appstract\Options\Option;
use Appstract\Options\OptionObserver;
use Illuminate\Support\Carbon;

class ObserveTest extends BaseTest
{
    public function setUp(): void
    {
        parent::setUp();
        $this->app['config']->set('laravel-options', [
            'cache' => [
                'driver'        => 'array',
                'valid_minutes' => 1,
            ],

            'events' => [
                'created' => true,
                'updated' => true,
                'deleted' => true,
                'finding' => true,
                'found'   => true,
                'exists'  => true,
            ],
        ]);
    }

    /** @test */
    public function it_can_close_observe_events()
    {
        $this->app['config']->set('laravel-options.events', [
            'created' => false,
            'updated' => true,
            'deleted' => true,
            'finding' => false,
            'found'   => true,
            'exists'  => true,
        ]);

        Option::observe($this->app->make(OptionObserver::class));

        $name = Option::class;
        $events = $this->app['config']->get('laravel-options.events');
        foreach ($events as $event => $open) {
            if ($open) {
                $this->assertTrue(Option::getEventDispatcher()->hasListeners("eloquent.{$event}: {$name}"));
            } else {
                $this->assertFalse(Option::getEventDispatcher()->hasListeners("eloquent.{$event}: {$name}"));
            }
        }

        $this->assertFalse(Option::getEventDispatcher()->hasListeners("eloquent.created: {$name}"));
        $this->assertFalse(Option::getEventDispatcher()->hasListeners("eloquent.finding: {$name}"));
    }

    /** @test */
    public function it_can_set()
    {
        $config = $this->getObserverConfig();
        $cache = $config->get('cache');
        Option::observe(new OptionObserver($config));

        option(['foo' => 'bar']);
        $this->assertEquals('bar', $cache->get('foo'));
        $this->assertDatabaseHas('options', ['key' => 'foo', 'value' => 'bar']);

        sleep(2);
        $this->assertFalse($cache->has('foo'));
        $this->assertEquals(null, $cache->get('foo'));
        $this->assertDatabaseHas('options', ['key' => 'foo', 'value' => 'bar']);
    }

    /** @test */
    public function it_can_update()
    {
        $config = $this->getObserverConfig();
        $cache = $config->get('cache');
        Option::observe(new OptionObserver($config));

        option(['foo' => 'bar']);
        $this->assertEquals('bar', $cache->get('foo'));
        option(['foo' => 'foobar']);
        $this->assertEquals('foobar', $cache->get('foo'));
    }

    /** @test */
    public function it_can_remove()
    {
        $config = $this->getObserverConfig();
        $cache = $config->get('cache');
        Option::observe(new OptionObserver($config));

        option(['foo' => 'bar']);
        $this->assertEquals('bar', $cache->get('foo'));
        option()->remove('foo');
        $this->assertFalse($cache->has('foo'));
    }

    /** @test */
    public function it_can_judge_exists()
    {
        $config = $this->getObserverConfig();
        $cache = $config->get('cache');
        Option::observe(new OptionObserver($config));

        option(['foo' => 'bar']);
        $this->assertEquals('bar', $cache->get('foo'));

        Option::where('key', 'foo')->delete();
        $this->assertEquals('bar', $cache->get('foo'));

        $this->assertFalse(\option()->exists('foo'));
        $this->assertEquals(null, $cache->get('foo'));
        $this->assertFalse($cache->has('foo'));
    }

    /** @test */
    public function it_can_get()
    {
        $config = $this->getObserverConfig();
        $cache = $config->get('cache');
        Option::observe(new OptionObserver($config));

        \option(['foo' => 'bar']);
        $this->assertEquals('bar', $cache->get('foo'));

        Option::where('key', 'foo')->update(['value' => 'foobar']);
        $this->assertEquals('bar', $cache->get('foo'));

        // isDirty() === false, not to trigger ~updated` event
        \option(['foo' => 'foobar']);
        $this->assertEquals('bar', $cache->get('foo'));

        \option(['foo' => 'zoooo']);
        $this->assertEquals('zoooo', $cache->get('foo'));

        $this->assertEquals(null, \option('foobar'));
        Option::insert(['key' => 'foobar', 'value' => 'bar']);
        $this->assertEquals('bar', \option('foobar'));
        $this->assertEquals('bar', $cache->get('foobar'));
    }

    /** @test */
    public function it_can_set_valid_minutes()
    {
        $config = $this->getObserverConfig();
        $cache = $config->get('cache');
        Option::observe(new OptionObserver($config));

        \Option::setValidMinutes(Carbon::now()->addSecond(3))->set('foo', 'bar');
        $this->assertEquals('bar', $cache->get('foo'));
        usleep(500);
        $this->assertEquals('bar', $cache->get('foo'));
        usleep(500);
        $this->assertEquals('bar', $cache->get('foo'));
        sleep(3);
        $this->assertEquals(null, $cache->get('foo'));
        $this->assertFalse($cache->has('foo'));
    }

    public function getObserverConfig($valid = 0.02)
    {
        $config = $this->app->make(ObserverConfig::class);
        $config->set('valid_minutes', $valid);
        $cache = $config->get('cache');
        $cache->flush();

        return $config;
    }
}
