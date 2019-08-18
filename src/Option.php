<?php

namespace Appstract\Options;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Option
 */
class Option extends Model
{
    use ValidMinutesAttribute;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var [type]
     */
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * User exposed observable events.
     *
     * These are extra user-defined events observers may subscribe to.
     *
     * @var array
     */
    protected $observables = [
        'finding',
        'found',
        'exists',
    ];

    /**
     * Determine if the given option value exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function exists($key)
    {
        $exists = self::where('key', $key)->exists();

        $this->fill(['key' => $key])->fireModelEvent('exists', false);

        return $exists;
    }

    /**
     * Get the specified option value.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (($option = $this->fireModelEvent('finding', false)) instanceof Option) {
            $value = $option->value;
            $this->fill($option->getAttributes());
        } elseif ($option = self::where('key', $key)->first()) {
            $value = $option->value;
            $this->fill($option->getAttributes());
        } else {
            $value = $default;
        }

        $this->fireModelEvent('found', false);

        return $value;
    }

    /**
     * Set a given option value.
     *
     * @param  array|string  $key
     * @param  mixed   $value
     * @return void
     */
    public function set($key, $value = null)
    {
        $keys = is_array($key) ? $key : [$key => $value];

        foreach ($keys as $key => $value) {
            self::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // @todo: return the option
    }

    /**
     * Remove/delete the specified option value.
     *
     * @param  string  $key
     * @return bool
     */
    public function remove($key)
    {
        $isSuccess = (bool) self::where('key', $key)->delete();

        $this->fill(['key' => $key])->fireModelEvent('deleted', false);

        return $isSuccess;
    }

    /**
     * Override method to
     * @param array|object|string $class
     */
    public static function observe($class)
    {
        parent::observe($class);

        if (method_exists($class, 'getRemoveObservableEvents')) {
            $events = $class->getRemoveObservableEvents();
        } else {
            $events = [];
        }

        foreach ($events as $event) {
            static::removeModelEvent($event);
        }
    }

    /**
     * Remove a model event with the dispatcher
     * @param $event
     */
    public static function removeModelEvent($event)
    {
        if (isset(static::$dispatcher)) {
            $name = get_called_class();

            static::$dispatcher->forget("eloquent.{$event}: {$name}");
        }
    }
}
