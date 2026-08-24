<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Order extends Model
{
    use LogsActivity;

    protected $fillable = [
        'order_code',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'delivery_type',
        'address_detail',
        'province_id',
        'regency_id',
        'district_id',
        'village_id',
        'postal_code',
        'latitude',
        'longitude',
        'subtotal',
        'shipping_cost',
        'total',
        'status',
        'payment_type',
        'down_payment',
        'remaining_payment',
        'payment_method',
        'payment_status',
        'midtrans_order_id',
        'midtrans_token',
        'midtrans_response',
        'paid_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'total' => 'decimal:2',
            'down_payment' => 'decimal:2',
            'remaining_payment' => 'decimal:2',
            'midtrans_response' => 'array',
            'paid_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function ($order) {
            if (empty($order->order_code)) {
                $date = now()->format('Ymd');
                $lastOrder = static::whereDate('created_at', now()->toDateString())->latest('id')->first();
                $sequence = $lastOrder ? ((int) substr($lastOrder->order_code, -4)) + 1 : 1;
                $order->order_code = sprintf('ORD-%s-%04d', $date, $sequence);
            }
        });
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'payment_status', 'total', 'paid_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function getFullAddressAttribute(): string
    {
        if (empty($this->province_id) && empty($this->regency_id)) {
            return ($this->address_detail ?: '-') . ($this->postal_code ? ' ' . $this->postal_code : '');
        }

        // Check if stored values are already text names (e.g. "Jawa Tengah", "Jepara")
        $isNumeric = is_numeric($this->province_id) || is_numeric($this->regency_id);

        if (!$isNumeric) {
            $parts = array_filter([
                $this->address_detail,
                $this->village_id,
                $this->district_id,
                $this->regency_id,
                $this->province_id,
            ]);
            $addr = implode(', ', $parts);
            return $this->postal_code ? $addr . ' ' . $this->postal_code : $addr;
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

        $addr = implode(', ', $parts);
        return $this->postal_code ? $addr . ' ' . $this->postal_code : $addr;
    }
}
