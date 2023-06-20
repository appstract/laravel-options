<?php

namespace Appstract\Options;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Encryption\DecryptException;

class Option extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Casts.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'json',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * The attributes that are visible.
     *
     * @var array
     */
    protected $visible = [
        'key',
        'value',
    ];

    /**
     * Determine if the given option value exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function exists($key)
    {
        return self::where('key', $key)->exists();
    }

    /**
     * Get the specified option value.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if ($option = self::where('key', $key)->first()) {
            $value = $option->encrypted() ? decrypt($option->value) : $option->value;

            return is_array($value) ? (object) $value : $value;
        }

        return $default;
    }

    /**
     * Set a given option value.
     *
     * @param  array|string  $key
     * @param  mixed  $value
     * @return \Appstract\Options\Option
     */
    public function set($key, $value = null)
    {
        $keys = is_array($key) ? $key : [$key => $value];

        foreach ($keys as $key => $value) {
            $option = self::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return $option;
    }

    /**
     * Remove/delete the specified option value.
     *
     * @param  string  $key
     * @return bool
     */
    public function remove($key)
    {
        return (bool) self::where('key', $key)->delete();
    }

    /**
     * Encrypt option value after set() is called.
     *
     * @return \Appstract\Options\Option
     */
    public function crypt()
    {
        self::where('key', $this->key)
            ->update(['value' => json_encode(encrypt($this->value))]);

        return $this;
    }

    /**
     * Determine if the option value is encrypted.
     *
     * @return bool
     */
    public function encrypted()
    {
        try {
            return (bool) decrypt($this->value);
        } catch (DecryptException $e) {
            return false;
        }
    }
}
