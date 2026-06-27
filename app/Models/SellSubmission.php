<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SellSubmission extends Model
{
    use LogsActivity;

    protected $fillable = [
        'submission_code',
        'customer_name',
        'customer_phone',
        'customer_whatsapp',
        'customer_city',
        'category_id',
        'device_brand',
        'device_model',
        'condition',
        'description',
        'offered_price',
        'agreed_price',
        'status',
        'admin_notes',
        'physical_check_at',
        'payment_at',
        'converted_product_id',
    ];

    protected function casts(): array
    {
        return [
            'offered_price' => 'decimal:2',
            'agreed_price' => 'decimal:2',
            'physical_check_at' => 'datetime',
            'payment_at' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'offered_price', 'agreed_price', 'admin_notes', 'converted_product_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
