<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ApplicationSettingsService
{
    const CACHE_KEY = 'application_settings';
    const CACHE_TTL = 86400; // 24 hours

    public static function load()
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return DB::table('application_settings')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [
                        $item->key => self::castValue($item->value, $item->type)
                    ];
                })
                ->toArray();
        });
    }

    public static function get(string $key, $default = null)
    {
        $settings = self::load();
        return $settings[$key] ?? $default;
    }

    protected static function castValue($value, $type)
    {
        return match ($type) {
            'int'   => (int) $value,
            'float' => (float) $value,
            'bool'  => (bool) $value,
            'json'  => json_decode($value, true),
            default => $value,
        };
    }

    public static function clearCache()
    {
        Cache::forget(self::CACHE_KEY);
    }
}
