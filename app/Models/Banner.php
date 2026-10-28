<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'hide_text_mobile' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }

    public function getMobileImageUrlAttribute(): ?string
    {
        return $this->image_mobile_path ? asset('storage/' . $this->image_mobile_path) : null;
    }
}
