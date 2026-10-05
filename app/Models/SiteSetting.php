<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
    ];

    public const CACHE_KEY = 'site_settings.all';

    protected static function booted(): void
    {
        static::saved(fn () => static::flushCache());
        static::deleted(fn () => static::flushCache());
    }

    public static function flushCache(): void
    {
        // `Cache::memo()->forget()` membersihkan dua lapis sekaligus: salinan
        // request yang berjalan dan entri di store. Menempelkan
        // `Cache::forget()` saja tidak cukup karena tidak menyentuh salinan,
        // sehingga request yang sama dengan penyimpanan admin masih bisa
        // membaca nilai lama.
        Cache::memo()->forget(self::CACHE_KEY);
    }

    public static function allCached(): array
    {
        // `Cache::memo()` menyimpan hasil baca di memori selama satu siklus
        // request, lalu meneruskan ke store yang biasa (database) saat miss.
        // Setiap halaman memuat puluhan `Site::...()`, tanpa ini setiap
        // pemanggilan jadi satu query ke tabel cache. Store di bawahnya tetap
        // `rememberForever`, jadi tidak ada perubahan perilaku antar-request.
        return Cache::memo()->rememberForever(self::CACHE_KEY, function () {
            return static::query()->pluck('value', 'key')->all();
        });
    }

    public static function value(string $key, mixed $default = null): mixed
    {
        $value = static::allCached()[$key] ?? null;

        return $value === null || $value === '' ? $default : $value;
    }
}
