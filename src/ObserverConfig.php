<?php

namespace Appstract\Options;

use Illuminate\Support\Collection;

class ObserverConfig
{
    protected $config;

    public function __construct(array $config)
    {
        $this->config = Collection::make($config);
    }

    public function get($key, $default = null)
    {
        return $this->config->get($key, $default);
    }

    public function set($key, $value)
    {
        $this->config->put($key, $value);
    }
}
