<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'type'];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('settings'));
        static::deleted(fn () => Cache::forget('settings'));
    }

    /**
     * Semua pengaturan dalam bentuk [key => value], di-cache agar tidak query tiap request.
     */
    public static function allCached(): array
    {
        return Cache::rememberForever('settings', fn () => static::pluck('value', 'key')->toArray());
    }

    public static function getValue(string $key, $default = null)
    {
        $value = static::allCached()[$key] ?? null;

        return ($value === null || $value === '') ? $default : $value;
    }

    /**
     * Nomor WhatsApp dalam format internasional (62xxx) untuk link wa.me.
     */
    public static function whatsappNumber(): string
    {
        $number = preg_replace('/\D/', '', (string) static::getValue('whatsapp_number', static::getValue('contact_phone', '')));

        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }

        return $number;
    }
}
