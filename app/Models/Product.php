<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

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

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getImageUrlAttribute()
    {
        $primaryImage = $this->images()->where('is_primary', true)->first();
        if ($primaryImage) {
            return asset('storage/' . $primaryImage->image_path);
        }

        $firstImage = $this->images()->first();
        if ($firstImage) {
            return asset('storage/' . $firstImage->image_path);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=e2e8f0&color=64748b';
    }
}
