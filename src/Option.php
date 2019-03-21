<?php

namespace Appstract\Options;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
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
        'type',
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
     * @param  mixed   $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if ($option = self::where('key', $key)->first()) {
            return $option->value;
        }

        return $default;
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

        foreach ($keys as $k => $v) {
            $arr = ['key' => $k, 'value' => $key[$k][0]];
            if (array_key_exists(1, $key[$k])) {
                $arr ['type'] = $key[$k][1];
            }
            self::updateOrCreate($arr);
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
        return (bool) self::where('key', $key)->delete();
    }
}
