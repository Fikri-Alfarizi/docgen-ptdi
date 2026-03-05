<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    protected $fillable = ['key', 'value'];

    protected static ?array $cachedSettings = null;

    public static function get($key, $default = null)
    {
        if (static::$cachedSettings === null) {
            static::$cachedSettings = Cache::remember('system_settings_all', 3600, function () {
                return self::all()->pluck('value', 'key')->toArray();
            });
        }

        return static::$cachedSettings[$key] ?? $default;
    }

    /**
     * Flush the settings cache (call after any update).
     */
    public static function flushCache(): void
    {
        Cache::forget('system_settings_all');
        static::$cachedSettings = null;
    }
}
