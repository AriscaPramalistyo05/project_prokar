<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'brand',
        'model',
        'description',
        'condition_notes',
        'condition',
        'condition_color',
        'price',
        'promo_price',
        'stock',
        'weight',
        'length',
        'width',
        'height',
        'status',
        'is_promo',
        'meta_title',
        'meta_description',
    ];

    /**
     * Returns the chargeable weight in grams.
     * Uses only the actual physical weight stored in the database (in grams).
     * Volumetric weight has been disabled because cargo couriers for electronics
     * use physical weight only and the box dimensions are already accounted for
     * in the cargo service minimums (min 10kg).
     */
    public function getChargeableWeightGram(): int
    {
        return (int) max(1000, $this->weight ?: 1000);
    }

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'promo_price' => 'decimal:2',
            'is_promo' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'price', 'status', 'stock'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // ── Scopes ──
    public function scopePromo($query)
    {
        return $query->where('is_promo', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    // ── Relations ──
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function getImageUrlAttribute(): string
    {
        $primary = $this->primaryImage ?? $this->productImages->where('is_primary', true)->first() ?? $this->productImages->first();
        if ($primary) {
            return $primary->url;
        }

        return 'https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=400&q=80';
    }
}
