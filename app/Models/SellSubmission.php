<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SellSubmission extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'submission_code',
        'customer_name',
        'customer_phone',
        'customer_whatsapp',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
        'address_detail',
        'category_id',
        'device_brand',
        'device_model',
        'condition',
        'description',
        'offered_price',
        'agreed_price',
        'status',
        'payment_method',
        'admin_notes',
        'physical_check_at',
        'payment_at',
        'converted_product_id',
    ];

    protected $casts = [
        'physical_check_at' => 'datetime',
        'payment_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'status',
                'offered_price',
                'agreed_price',
                'condition',
                'device_brand',
                'device_model',
                'converted_product_id'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->submission_code)) {
                $model->submission_code = 'SELL-' . date('Ymd') . '-' .
                    str_pad(self::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'converted_product_id');
    }

    public function sellSubmissionImages(): HasMany
    {
        return $this->hasMany(SellSubmissionImage::class);
    }
}
