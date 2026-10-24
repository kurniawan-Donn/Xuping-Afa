<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public const BADGES = [
        'new' => 'Baru',
        'promo' => 'Promo',
        'bestseller' => 'Terlaris',
    ];

    public const COLORS = [
        'Gold' => '#d4af37',
        'Silver' => '#c0c0c0',
        'Rose Gold' => '#b76e79',
    ];

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'sizes' => 'array',
        'colors' => 'array',
    ];

    protected static function booted(): void
    {
        // Chatbot memakai katalog ringkas yang di-cache; perbarui saat produk berubah
        static::saved(fn () => \Illuminate\Support\Facades\Cache::forget(\App\Support\Chatbot::CATALOG_CACHE_KEY));
        static::deleted(fn () => \Illuminate\Support\Facades\Cache::forget(\App\Support\Chatbot::CATALOG_CACHE_KEY));
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('stock', '>', 0);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderByDesc('is_primary')->orderBy('sort_order');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getImageUrlAttribute(): string
    {
        $image = $this->images->first();

        if ($image) {
            return asset('storage/' . $image->image_path);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=f5efe6&color=a07c3b&size=512';
    }

    public function getInStockAttribute(): bool
    {
        return $this->stock > 0;
    }

    public function getHasDiscountAttribute(): bool
    {
        return $this->compare_price && $this->compare_price > $this->price;
    }

    public function getDiscountPercentAttribute(): int
    {
        return $this->has_discount ? (int) round(100 - ($this->price / $this->compare_price * 100)) : 0;
    }

    public function getBadgeLabelAttribute(): ?string
    {
        return self::BADGES[$this->badge] ?? null;
    }

    public function getHasVariantsAttribute(): bool
    {
        return ! empty($this->sizes) || ! empty($this->colors);
    }

    /**
     * Data ringkas yang disimpan di Daftar Pesanan (localStorage pengunjung).
     */
    public function cartPayload(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => (float) $this->price,
            'image' => $this->image_url,
            'url' => route('website.products.show', $this),
        ];
    }

    public function getShortDescriptionAttribute(): string
    {
        return \Illuminate\Support\Str::limit(strip_tags((string) $this->description), 155);
    }
}
