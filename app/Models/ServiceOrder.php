<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ServiceOrder extends Model
{
    use LogsActivity;

    protected $fillable = [
        'service_code',
        'user_id',
        'technician_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'service_type',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
        'address_detail',
        'latitude',
        'longitude',
        'category_id',
        'device_brand',
        'device_model',
        'complaint',
        'diagnosis',
        'estimated_cost',
        'final_cost',
        'status',
        'customer_approval',
        'approved_at',
        'completed_at',
        'warranty_until',
        'payment_status',
        'paid_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'estimated_cost' => 'decimal:2',
            'final_cost' => 'decimal:2',
            'approved_at' => 'datetime',
            'completed_at' => 'datetime',
            'warranty_until' => 'date',
            'paid_at' => 'datetime',
        ];
    }

    // ── Auto-generate service_code ──

    // ── Activity Log ──
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'diagnosis', 'estimated_cost', 'final_cost', 'customer_approval', 'payment_status', 'technician_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // ── Relations ──
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function serviceImages()
    {
        return $this->hasMany(ServiceImage::class);
    }

    public function serviceStatusLogs()
    {
        return $this->hasMany(ServiceStatusLog::class);
    }

    public function getFullAddressAttribute()
    {
        if (!$this->province_id) {
            return $this->address_detail ?: '-';
        }

        $province = \Illuminate\Support\Facades\Cache::remember("prov_{$this->province_id}", 86400, function () {
            $res = @file_get_contents("https://www.emsifa.com/api-wilayah-indonesia/api/province/{$this->province_id}.json");
            return $res ? json_decode($res)->name ?? '' : '';
        });

        $regency = \Illuminate\Support\Facades\Cache::remember("reg_{$this->regency_id}", 86400, function () {
            $res = @file_get_contents("https://www.emsifa.com/api-wilayah-indonesia/api/regency/{$this->regency_id}.json");
            return $res ? json_decode($res)->name ?? '' : '';
        });

        $district = \Illuminate\Support\Facades\Cache::remember("dist_{$this->district_id}", 86400, function () {
            $res = @file_get_contents("https://www.emsifa.com/api-wilayah-indonesia/api/district/{$this->district_id}.json");
            return $res ? json_decode($res)->name ?? '' : '';
        });

        $village = \Illuminate\Support\Facades\Cache::remember("vill_{$this->village_id}", 86400, function () {
            $res = @file_get_contents("https://www.emsifa.com/api-wilayah-indonesia/api/village/{$this->village_id}.json");
            return $res ? json_decode($res)->name ?? '' : '';
        });

        $parts = array_filter([$this->address_detail, $village, $district, $regency, $province]);
        return implode(', ', $parts);
    }
}
