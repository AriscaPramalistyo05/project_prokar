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
        'payment_method',
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

    public function serviceFees()
    {
        return $this->hasMany(ServiceFee::class);
    }

    public function getFullAddressAttribute()
    {
        if (!$this->province_id) {
            return $this->address_detail ?: '-';
        }

        // Check if stored values are already text names
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
        return implode(', ', $parts);
    }
}
