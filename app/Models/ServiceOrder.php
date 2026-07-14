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
        'customer_address',
        'customer_city',
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
}
