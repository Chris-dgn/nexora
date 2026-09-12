<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ProductTranslation;
use App\Models\Review;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'compare_at_price',
        'stock',
        'sku',
        'category_id',
        'brand',
        'is_active',
    ];

    public function getRouteKeyName(): string
{
    return 'slug';
}

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_at_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function translations(): HasMany
{
    return $this->hasMany(ProductTranslation::class);
}

public function translated(): ?ProductTranslation
{
    return $this->translations->firstWhere('locale', app()->getLocale());
}

public function getTranslatedNameAttribute(): string
{
    return $this->translated()?->name ?? $this->name;
}

public function getTranslatedDescriptionAttribute(): ?string
{
    return $this->translated()?->description ?? $this->description;
}

public function reviews(): HasMany
{
    return $this->hasMany(Review::class);
}

public function approvedReviews(): HasMany
{
    return $this->reviews()->where('is_approved', true);
}

public function getAverageRatingAttribute(): ?float
{
    return $this->approvedReviews()->avg('rating');
}

public function getReviewsCountAttribute(): int
{
    return $this->approvedReviews()->count();
}
}