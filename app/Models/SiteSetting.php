<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $table = 'site_settings';

    protected $fillable = ['key', 'value'];

    const CACHE_KEY = 'site_settings_all';

    /**
     * Get a single setting value by key, with a fallback default
     * if it hasn't been set yet (e.g. before the seeder runs).
     */
    public static function get(string $key, ?string $default = null): ?string
    {
        $all = static::allCached();
        return $all[$key] ?? $default;
    }

    /**
     * Set (create or update) a single setting.
     */
    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Set many settings at once from an associative array [key => value].
     */
    public static function setMany(array $pairs): void
    {
        foreach ($pairs as $key => $value) {
            static::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * All settings as a flat [key => value] array, cached briefly since
     * this is read on nearly every About/Contact page load.
     */
    protected static function allCached(): array
    {
        return Cache::remember(self::CACHE_KEY, 3600, function () {
            return static::pluck('value', 'key')->all();
        });
    }
}
