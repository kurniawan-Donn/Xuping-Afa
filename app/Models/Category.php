<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [
        'id',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => \Illuminate\Support\Facades\Cache::forget(\App\Support\Chatbot::CATALOG_CACHE_KEY));
        static::deleted(fn () => \Illuminate\Support\Facades\Cache::forget(\App\Support\Chatbot::CATALOG_CACHE_KEY));
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
