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
        'customer_address',
        'customer_city',
        'subtotal',
        'shipping_cost',
        'total',
        'status',
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
            'midtrans_response' => 'array',
            'paid_at' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'payment_status', 'total', 'paid_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
