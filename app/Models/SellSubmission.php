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
        'user_id',
        'customer_name',
        'customer_email',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
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

    public function getFullAddressAttribute(): string
    {
        if (!$this->province_id) {
            return $this->address_detail ?: ($this->customer_city ?? '-');
        }

        $isNumeric = is_numeric($this->province_id) || is_numeric($this->regency_id);

        if (!$isNumeric) {
            $parts = array_filter([
                $this->address_detail,
                $this->village_id,
                $this->district_id,
                $this->regency_id,
                $this->province_id,
            ]);
            return implode(', ', $parts);
        }

        $province = \Illuminate\Support\Facades\Cache::remember("prov_{$this->province_id}", 86400 * 30, function () {
            if (!$this->province_id) return '';
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(0.4)->get("https://www.emsifa.com/api-wilayah-indonesia/api/province/{$this->province_id}.json");
                return $response->successful() ? ($response->json('name') ?? '') : '';
            } catch (\Throwable $e) {
                return '';
            }
        });

        $regency = \Illuminate\Support\Facades\Cache::remember("reg_{$this->regency_id}", 86400 * 30, function () {
            if (!$this->regency_id) return '';
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(0.4)->get("https://www.emsifa.com/api-wilayah-indonesia/api/regency/{$this->regency_id}.json");
                return $response->successful() ? ($response->json('name') ?? '') : '';
            } catch (\Throwable $e) {
                return '';
            }
        });

        $district = \Illuminate\Support\Facades\Cache::remember("dist_{$this->district_id}", 86400 * 30, function () {
            if (!$this->district_id) return '';
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(0.4)->get("https://www.emsifa.com/api-wilayah-indonesia/api/district/{$this->district_id}.json");
                return $response->successful() ? ($response->json('name') ?? '') : '';
            } catch (\Throwable $e) {
                return '';
            }
        });

        $village = \Illuminate\Support\Facades\Cache::remember("vill_{$this->village_id}", 86400 * 30, function () {
            if (!$this->village_id) return '';
            try {
                $response = \Illuminate\Support\Facades\Http::timeout(0.4)->get("https://www.emsifa.com/api-wilayah-indonesia/api/village/{$this->village_id}.json");
                return $response->successful() ? ($response->json('name') ?? '') : '';
            } catch (\Throwable $e) {
                return '';
            }
        });

        $parts = array_filter([
            $this->address_detail,
            $village ? ucwords(strtolower($village)) : null,
            $district ? 'Kec. ' . ucwords(strtolower($district)) : null,
            $regency ? ucwords(strtolower($regency)) : null,
            $province ? ucwords(strtolower($province)) : null,
        ]);
        return implode(', ', $parts) ?: '-';
    }
}
