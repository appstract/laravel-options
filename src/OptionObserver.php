<?php


namespace Appstract\Options;

use Illuminate\Support\Traits\Macroable;

class OptionObserver
{
    use Macroable;

    /**
     * @var \Psr\SimpleCache\CacheInterface
     */
    protected $cache;

    /**
     * @var string[]
     */
    protected $removeObservables = array();

    /**
     * @var int|float|string
     */
    protected $defaultValidMinutes;

    /**
     * OptionObserver constructor.
     */
    public function __construct(ObserverConfig $config)
    {
        $this->cache = $config->get('cache');
        $this->defaultValidMinutes = $config->get('valid_minutes');
        $this->removeObservables = $config->get('except_events');
    }

    public function created(Option $option)
    {
        $this->cache->set($this->buildKey($option->key), $option->value,
            $this->getValidMinutes($option->validMinutes));
    }

    public function updated(Option $option)
    {
        $this->cache->set($this->buildKey($option->key), $option->value,
            $this->getValidMinutes($option->validMinutes));
    }

    public function deleted(Option $option)
    {
        $this->cache->delete($this->buildKey($option->key));
    }

    public function finding(Option $option)
    {
        if ($this->cache->has($key = $this->buildKey($option->key))) {
            return new Option([
                'key'   => $option->key,
                'value' => $this->cache->get($key),
            ]);
        }

        return null;
    }

    public function found(Option $option)
    {
        if (!$this->cache->has($key = $this->buildKey($option->key))) {
            $this->cache->set($key, $option->value, $this->getValidMinutes($option->validMinutes));
        }
    }

    public function exists(Option $option)
    {
        if ($option->exists) {
            $this->updated($option);
        } else {
            $this->deleted($option);
        }
    }

    protected function getValidMinutes($minutes = null)
    {
        return $minutes ?: $this->defaultValidMinutes;
    }

    /**
     * @param string $key
     * @return string
     */
    protected function buildKey($key)
    {
        // todo: hash key
        return $key;
    }

    /**
     * @return array
     */
    public function getRemoveObservableEvents()
    {
        return $this->removeObservables;
    }
}
